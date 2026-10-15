<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CarDetailRequest;
use App\Http\Requests\Car\CarFilterRequest;
use App\Responses\ApiResponse;
use App\Services\Car\CarService;
use App\Services\Car\DTO\CarDetailDTO;
use App\Services\Car\DTO\CarFilterDTO;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function __construct(protected CarService $carService)
    {
    }

    public function index(CarFilterRequest $request): JsonResponse
    {
        $dto = CarFilterDTO::fromRequest($request);
        $paginator = $this->carService->getCars($dto);

        return (new ApiResponse())
            ->setData($paginator)
            ->create();
    }

    public function show(int $id, CarDetailRequest $request): JsonResponse
    {
        $dto = CarDetailDTO::fromRequest($id, $request);
        $car = $this->carService->getCarDetails($dto);

        return (new ApiResponse())
            ->setData($car)
            ->create();
    }
}
