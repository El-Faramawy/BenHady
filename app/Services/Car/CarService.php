<?php

declare(strict_types=1);

namespace App\Services\Car;

use App\Models\Car;
use App\Repositories\CarRepository;
use App\Services\Car\DTO\CarDetailDTO;
use App\Services\Car\DTO\CarFilterDTO;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class CarService
{
    use UploadTrait;

    public function __construct(protected CarRepository $carRepository)
    {
    }

    public function getCars(CarFilterDTO $dto): LengthAwarePaginator
    {
        return $this->carRepository->paginateCars($dto);
    }

    public function getCarDetails(CarDetailDTO|int $dto, ?int $cityId = null): Car|array
    {
        if (is_int($dto)) {
            return $this->carRepository->findActiveByIdOrFail($dto, $cityId);
        }

        return $this->carRepository->findActiveByIdOrFail($dto->id, $dto->cityId);
    }

    /**
     * Get DataTable response for Admin cars list.
     */
    public function getDataTable(): JsonResponse
    {
        $query = $this->carRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Car $car): string => tableCheckbox($car->id))
            ->addColumn('primary_img', fn (Car $car): string => tableImage($car->primaryImage?->image_path, $car->name_ar))
            ->addColumn('name_formatted', function (Car $car): string {
                return '<div class="font-weight-bold text-dark font-size-14">' . htmlspecialchars($car->name_ar, ENT_QUOTES, 'UTF-8') . '</div>' .
                    '<small class="text-muted" dir="ltr">' . htmlspecialchars($car->name_en, ENT_QUOTES, 'UTF-8') . '</small>';
            })
            ->addColumn('brand_category', function (Car $car): string {
                $brand = htmlspecialchars($car->brand?->name_ar ?? '-', ENT_QUOTES, 'UTF-8');
                $cat = htmlspecialchars($car->category?->name_ar ?? '-', ENT_QUOTES, 'UTF-8');
                return '<div class="font-weight-bold">' . $brand . '</div><small class="badge badge-light">' . $cat . '</small>';
            })
            ->addColumn('branch_name', fn (Car $car): string => htmlspecialchars($car->branch?->name_ar ?? '-', ENT_QUOTES, 'UTF-8'))
            ->addColumn('model_year_val', fn (Car $car): string => '<span class="badge badge-light px-2 py-1 font-weight-bold font-size-12">' . ($car->modelYear?->year ?? '-') . '</span>')
            ->addColumn('daily_price_formatted', fn (Car $car): string => '<strong class="text-success font-size-14">' . number_format((float) $car->daily_price, 2) . '</strong> <small class="text-muted">ر.س / يوم</small>')
            ->addColumn('seats_formatted', fn (Car $car): string => '<span class="font-weight-bold text-dark">' . $car->seats . ' <i class="fa fa-users text-muted font-size-11"></i></span>')
            ->addColumn('car_status_badge', function (Car $car): string {
                $statusVal = is_object($car->status) ? $car->status->value : (string) $car->status;
                return match ($statusVal) {
                    'available'   => '<span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-check-circle ml-1"></i>متاحة</span>',
                    'rented'      => '<span class="badge badge-warning px-2 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-clock-o ml-1"></i>مؤجرة</span>',
                    'maintenance' => '<span class="badge badge-danger px-2 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-wrench ml-1"></i>صيانة</span>',
                    'reserved'    => '<span class="badge badge-info px-2 py-1 font-weight-bold" style="border-radius: 6px;"><i class="fa fa-bookmark ml-1"></i>محجوزة</span>',
                    default       => '<span class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px;">' . htmlspecialchars($statusVal, ENT_QUOTES, 'UTF-8') . '</span>',
                };
            })
            ->addColumn('is_active_badge', fn (Car $car): string => tableStatus((bool) $car->is_active))
            ->addColumn('action', function (Car $car): string {
                $detailsBtn = '<a href="' . route('cars.show', $car->id) . '" class="btn btn-default btn-action-details btn-sm mb-2 mb-xl-0" data-toggle="tooltip" title="تفاصيل السيارة ومواصفاتها"><i class="fa fa-eye text-white"></i></a> ';
                return $detailsBtn . tableAction($car->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a new car from Admin with features, branches, and images.
     *
     * @param array<string, mixed> $data
     * @param array<int> $features
     * @param array<int> $branches
     * @param array<UploadedFile> $galleryImages
     */
    public function store(array $data, ?UploadedFile $primaryImage = null, array $galleryImages = [], array $features = [], array $branches = []): Car
    {
        $car = $this->carRepository->create($data);

        if (!empty($features)) {
            $this->carRepository->syncFeatures($car, $features);
        }

        if (!empty($branches)) {
            $this->carRepository->syncBranches($car, $branches);
        }

        if ($primaryImage !== null) {
            $path = $this->uploadImage($primaryImage, 'cars');
            if ($path) {
                $this->carRepository->addImage($car, $path, true, 0);
            }
        }

        if (!empty($galleryImages)) {
            foreach ($galleryImages as $index => $galleryImage) {
                if ($galleryImage instanceof UploadedFile) {
                    $path = $this->uploadImage($galleryImage, 'cars');
                    if ($path) {
                        $this->carRepository->addImage($car, $path, false, $index + 1);
                    }
                }
            }
        }

        return $car;
    }

    /**
     * Update an existing car from Admin.
     *
     * @param array<string, mixed> $data
     * @param array<int> $features
     * @param array<int> $branches
     * @param array<UploadedFile> $galleryImages
     * @param array<int> $deleteImageIds
     */
    public function update(
        Car $car,
        array $data,
        ?UploadedFile $primaryImage = null,
        array $galleryImages = [],
        array $features = [],
        array $branches = [],
        array $deleteImageIds = []
    ): bool {
        $this->carRepository->update($car, $data);

        $this->carRepository->syncFeatures($car, $features);
        $this->carRepository->syncBranches($car, $branches);

        if (!empty($deleteImageIds)) {
            foreach ($deleteImageIds as $imageId) {
                $this->carRepository->deleteImage($car, (int) $imageId);
            }
        }

        if ($primaryImage !== null) {
            $path = $this->uploadImage($primaryImage, 'cars');
            if ($path) {
                $this->carRepository->addImage($car, $path, true, 0);
            }
        }

        if (!empty($galleryImages)) {
            $currentCount = $car->images()->count();
            foreach ($galleryImages as $index => $galleryImage) {
                if ($galleryImage instanceof UploadedFile) {
                    $path = $this->uploadImage($galleryImage, 'cars');
                    if ($path) {
                        $this->carRepository->addImage($car, $path, false, $currentCount + $index + 1);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Delete car.
     */
    public function delete(Car $car): bool
    {
        return $this->carRepository->delete($car);
    }

    /**
     * Batch delete cars.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->carRepository->deleteMultiple($ids);
    }

    /**
     * Delete a single image from car.
     */
    public function deleteImage(Car $car, int $imageId): bool
    {
        return $this->carRepository->deleteImage($car, $imageId);
    }

    /**
     * Set image as primary.
     */
    public function setPrimaryImage(Car $car, int $imageId): bool
    {
        return $this->carRepository->setPrimaryImage($car, $imageId);
    }

    /**
     * Get car with full details for showcase.
     */
    public function getCarWithFullDetails(Car $car): Car
    {
        return $this->carRepository->getCarWithFullDetails($car);
    }

    /**
     * Get all cars for visual catalog.
     *
     * @return Collection<int, Car>
     */
    public function getAllCarsForCatalog(): Collection
    {
        return $this->carRepository->getAllCarsForCatalog();
    }
}
