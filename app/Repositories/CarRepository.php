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
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
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
                'brand:id,name_ar,name_en,logo_url',
                'category:id,name_ar,name_en',
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
                                        $standardTime->whereColumn('opening_time', '<=', 'closing_time')
                                            ->whereTime('opening_time', '<=', $time)
                                            ->whereTime('closing_time', '>=', $time);
                                    })
                                    ->orWhere(function (Builder $overnightTime) use ($time) {
                                        $overnightTime->whereColumn('opening_time', '>', 'closing_time')
                                            ->where(function (Builder $orNight) use ($time) {
                                                $orNight->whereTime('opening_time', '<=', $time)
                                                    ->orWhereTime('closing_time', '>=', $time);
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

    public function findActiveByIdOrFail(int $id): Car
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
            ])
            ->first();

        if (!$car) {
            throw new CarNotFoundException();
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
}
