<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\Car\CarNotFoundException;
use App\Models\Car;
use App\Services\Car\DTO\CarFilterDTO;
use App\Services\Home\DTO\HomeFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class CarRepository
{
    public function __construct(protected Car $model)
    {
    }

    /**
     * @return Collection<int, Car>
     */
    public function getFeaturedCars(?HomeFilterDTO $filter = null, int $limit = 6): Collection
    {
        $query = $this->model
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with([
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'primaryImage:id,car_id,image_path',
            ]);

        $this->applyHomeFilter($query, $filter);

        return $query->take($limit)->get();
    }

    /**
     * @return Collection<int, Car>
     */
    public function getHandpickedCars(?HomeFilterDTO $filter = null, int $limit = 6): Collection
    {
        $query = $this->model
            ->where('is_active', true)
            ->where('is_handpicked', true)
            ->with([
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'primaryImage:id,car_id,image_path',
            ]);

        $this->applyHomeFilter($query, $filter);

        return $query->take($limit)->get();
    }

    protected function applyHomeFilter(Builder $query, ?HomeFilterDTO $filter): void
    {
        if ($filter === null || !$filter->hasFilters()) {
            return;
        }

        $cityId = $filter->cityId;
        $dayOfWeek = $filter->pickupDate ? strtolower(Carbon::parse($filter->pickupDate)->format('l')) : null;
        $time = $filter->pickupTime ? substr($filter->pickupTime, 0, 5) : null;

        $query->whereHas('branch', function (Builder $b) use ($cityId, $dayOfWeek, $time) {
            $b->where('is_active', true);

            if ($cityId !== null) {
                $b->where('city_id', $cityId);
            }

            if ($dayOfWeek !== null) {
                $b->where(function (Builder $whQuery) use ($dayOfWeek, $time) {
                    $whQuery->whereHas('workingHours', function (Builder $wh) use ($dayOfWeek, $time) {
                        $wh->where('day_of_week', $dayOfWeek)
                            ->where('is_open', true);

                        if ($time !== null) {
                            $wh->where(function (Builder $timeQuery) use ($time) {
                                $timeQuery->where('is_24_hours', true)
                                    ->orWhere(function (Builder $standardTime) use ($time) {
                                        $standardTime->whereColumn('open_at', '<=', 'close_at')
                                            ->whereTime('open_at', '<=', $time)
                                            ->whereTime('close_at', '>=', $time);
                                    })
                                    ->orWhere(function (Builder $overnightTime) use ($time) {
                                        $overnightTime->whereColumn('open_at', '>', 'close_at')
                                            ->where(function (Builder $orNight) use ($time) {
                                                $orNight->whereTime('open_at', '<=', $time)
                                                    ->orWhereTime('close_at', '>=', $time);
                                            });
                                    });
                            });
                        }
                    })->orWhereDoesntHave('workingHours');
                });
            }
        });
    }

    public function paginateCars(CarFilterDTO $dto): LengthAwarePaginator
    {
        $query = $this->model
            ->where('is_active', true)
            ->with([
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
                'branch:id,city_id,name_ar,name_en',
                'branch.city:id,name_ar,name_en',
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'primaryImage:id,car_id,image_path',
            ]);

        $this->applyFilters($query, $dto);
        $this->applySorting($query, $dto);

        return $query->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    public function findActiveByIdOrFail(int $id, ?int $cityId = null): Car
    {
        $car = $this->model
            ->where('id', $id)
            ->where('is_active', true)
            ->with([
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
                'branch:id,city_id,name_ar,name_en,address_ar,address_en,phone,latitude,longitude',
                'branch.city:id,name_ar,name_en',
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'images:id,car_id,image_path,is_primary,sort_order',
                'features:id,name_ar,name_en,icon',
                'branches' => function ($query) use ($cityId) {
                    $query->where('branches.is_active', true)
                        ->with('city:id,name_ar,name_en');

                    if ($cityId !== null) {
                        $query->where('branches.city_id', $cityId);
                    }
                },
            ])
            ->first();

        if (!$car) {
            throw new CarNotFoundException();
        }

        if ($car->branches->isEmpty() && $car->branch) {
            if ($cityId === null || $car->branch->city_id === $cityId) {
                $car->setRelation('branches', collect([$car->branch]));
            }
        }

        return $car;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getSimilarCars(int $categoryId, int $excludeCarId, int $limit = 4): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->where('category_id', $categoryId)
            ->where('id', '!=', $excludeCarId)
            ->with([
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'primaryImage:id,car_id,image_path',
            ])
            ->take($limit)
            ->get();
    }

    protected function applyFilters(Builder $query, CarFilterDTO $dto): void
    {
        if ($dto->categoryId !== null) {
            $query->where('category_id', $dto->categoryId);
        }

        if ($dto->brandId !== null) {
            $query->where('brand_id', $dto->brandId);
        }

        if ($dto->branchId !== null) {
            $query->where('branch_id', $dto->branchId);
        } elseif ($dto->cityId !== null) {
            $query->whereHas('branch', function (Builder $b) use ($dto) {
                $b->where('city_id', $dto->cityId);
            });
        }

        if ($dto->modelYearId !== null) {
            $query->where('model_year_id', $dto->modelYearId);
        }

        if ($dto->transmissionId !== null) {
            $query->where('transmission_id', $dto->transmissionId);
        }

        if ($dto->fuelTypeId !== null) {
            $query->where('fuel_type_id', $dto->fuelTypeId);
        }

        if ($dto->minPrice !== null) {
            $query->where('daily_price', '>=', $dto->minPrice);
        }

        if ($dto->maxPrice !== null) {
            $query->where('daily_price', '<=', $dto->maxPrice);
        }

        if ($dto->seats !== null) {
            $query->where('seats', '>=', $dto->seats);
        }

        if ($dto->search !== null && trim($dto->search) !== '') {
            $term = '%' . trim($dto->search) . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('name_ar', 'like', $term)
                  ->orWhere('name_en', 'like', $term)
                  ->orWhereHas('brand', function (Builder $b) use ($term) {
                      $b->where('name_ar', 'like', $term)
                        ->orWhere('name_en', 'like', $term);
                  });
            });
        }
    }

    protected function applySorting(Builder $query, CarFilterDTO $dto): void
    {
        $sortOrder = $dto->sortOrder === 'desc' ? 'desc' : 'asc';

        match ($dto->sortBy) {
            'price' => $query->orderBy('daily_price', $sortOrder),
            'year' => $query->join('model_years', 'cars.model_year_id', '=', 'model_years.id')
                            ->orderBy('model_years.year', $sortOrder)
                            ->select('cars.*'),
            'created_at' => $query->orderBy('cars.created_at', $sortOrder),
            default => $query->orderBy('cars.id', 'desc'),
        };
    }

    /**
     * Get DataTable query builder for Admin car listing.
     */
    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()
            ->with([
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
                'branch:id,name_ar,name_en',
                'modelYear:id,year',
                'transmission:id,name_ar,name_en',
                'fuelType:id,name_ar,name_en',
                'primaryImage:id,car_id,image_path',
            ])
            ->orderBy('id', 'desc');
    }

    /**
     * Create a new car.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Car
    {
        return $this->model->create($data);
    }

    /**
     * Update car attributes.
     *
     * @param array<string, mixed> $data
     */
    public function update(Car $car, array $data): bool
    {
        return $car->update($data);
    }

    /**
     * Delete a car.
     */
    public function delete(Car $car): bool
    {
        return (bool) $car->delete();
    }

    /**
     * Delete multiple cars by IDs.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Sync car features.
     *
     * @param array<int> $featureIds
     */
    public function syncFeatures(Car $car, array $featureIds): void
    {
        $car->features()->sync($featureIds);
    }

    /**
     * Sync available pickup branches for car.
     *
     * @param array<int> $branchIds
     */
    public function syncBranches(Car $car, array $branchIds): void
    {
        $car->branches()->sync($branchIds);
    }

    /**
     * Add an image record to the car.
     */
    public function addImage(Car $car, string $imagePath, bool $isPrimary = false, int $sortOrder = 0): \App\Models\CarImage
    {
        if ($isPrimary) {
            $car->images()->update(['is_primary' => false]);
        }

        return $car->images()->create([
            'image_path' => $imagePath,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
        ]);
    }

    /**
     * Delete a car image.
     */
    public function deleteImage(Car $car, int $imageId): bool
    {
        $image = $car->images()->where('id', $imageId)->first();
        if (!$image) {
            return false;
        }

        $wasPrimary = $image->is_primary;
        $deleted = (bool) $image->delete();

        // If the primary image was deleted, make the first remaining image primary
        if ($deleted && $wasPrimary) {
            $firstRemaining = $car->images()->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_primary' => true]);
            }
        }

        return $deleted;
    }

    /**
     * Set a car image as primary.
     */
    public function setPrimaryImage(Car $car, int $imageId): bool
    {
        $target = $car->images()->where('id', $imageId)->first();
        if (!$target) {
            return false;
        }

        $car->images()->update(['is_primary' => false]);
        return (bool) $target->update(['is_primary' => true]);
    }

    /**
     * Load car with all relations for comprehensive Car Details view.
     */
    public function getCarWithFullDetails(Car $car): Car
    {
        return $car->load([
            'brand',
            'category',
            'branch.city',
            'branches.city',
            'modelYear',
            'transmission',
            'fuelType',
            'images',
            'features',
            'bookings' => fn ($q) => $q->with(['user', 'pickupBranch', 'returnBranch'])->orderBy('id', 'desc')->take(20),
        ]);
    }

    /**
     * Get all active cars with relations for visual catalog overview.
     *
     * @return Collection<int, Car>
     */
    public function getAllCarsForCatalog(): Collection
    {
        return $this->model
            ->with([
                'brand',
                'category',
                'branch.city',
                'modelYear',
                'transmission',
                'fuelType',
                'primaryImage',
                'images',
                'features',
            ])
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get total count of cars.
     */
    public function getTotalCount(): int
    {
        return $this->model->newQuery()->count();
    }

    /**
     * Get count of cars grouped by status.
     *
     * @return array<string, int>
     */
    public function getStatusCounts(): array
    {
        return [
            'available' => $this->model->newQuery()->where('status', 'available')->count(),
            'rented' => $this->model->newQuery()->where('status', 'rented')->count(),
            'maintenance' => $this->model->newQuery()->where('status', 'maintenance')->count(),
            'reserved' => $this->model->newQuery()->where('status', 'reserved')->count(),
        ];
    }

    /**
     * Get top rented cars with primary images.
     *
     * @return Collection<int, Car>
     */
    public function getTopRentedCars(int $limit = 5): Collection
    {
        return $this->model->newQuery()
            ->withCount('bookings')
            ->with(['brand', 'category', 'primaryImage', 'modelYear'])
            ->orderByDesc('bookings_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get car distribution by category.
     *
     * @return array<string, int>
     */
    public function getCategoryDistribution(): array
    {
        return $this->model->newQuery()
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->selectRaw('categories.name_ar as category_name, count(cars.id) as car_count')
            ->groupBy('categories.id', 'categories.name_ar')
            ->orderByDesc('car_count')
            ->pluck('car_count', 'category_name')
            ->toArray();
    }
}
