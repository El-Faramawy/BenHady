<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\FuelType\FuelTypeService;
use Illuminate\Http\JsonResponse;

class FuelTypeController extends Controller
{
    public function __construct(protected FuelTypeService $fuelTypeService)
    {
    }

    public function index(): JsonResponse
    {
        $fuelTypes = $this->fuelTypeService->getFuelTypes();

        return (new ApiResponse())
            ->setData($fuelTypes)
            ->create();
    }
}
