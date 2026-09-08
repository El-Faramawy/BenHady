<?php

declare(strict_types=1);

namespace App\Services\Car;

use App\Models\Car;
use App\Repositories\CarRepository;
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

    public function getCarDetails(int $id): array
    {
        $car = $this->carRepository->findActiveByIdOrFail($id);
        $similarCars = $this->carRepository->getSimilarCars($car->category_id, $car->id, 4);

        return [
            'car' => $car,
            'similar_cars' => $similarCars,
        ];
    }
}
