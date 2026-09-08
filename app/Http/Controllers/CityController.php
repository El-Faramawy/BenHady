<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\City\CityService;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function __construct(protected CityService $cityService)
    {
    }

    public function index(): JsonResponse
    {
        $cities = $this->cityService->getCities();

        return (new ApiResponse())
            ->setData($cities)
            ->create();
    }
}
