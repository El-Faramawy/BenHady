<?php

declare(strict_types=1);

namespace App\Services\Car;

use App\Models\Car;
use App\Repositories\CarRepository;
use App\Services\Car\DTO\CarDetailDTO;
use App\Services\Car\DTO\CarFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarService
{
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
}
