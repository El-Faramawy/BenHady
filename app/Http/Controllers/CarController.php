<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CarFilterRequest;
use App\Responses\ApiResponse;
use App\Services\Car\CarService;
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

    public function show(int $id): JsonResponse
    {
        $data = $this->carService->getCarDetails($id);

        return (new ApiResponse())
            ->setData($data)
            ->create();
    }
}
