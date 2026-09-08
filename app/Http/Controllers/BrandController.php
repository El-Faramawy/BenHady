<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\Brand\BrandService;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brandService)
    {
    }

    public function index(): JsonResponse
    {
        $brands = $this->brandService->getBrands();

        return (new ApiResponse())
            ->setData($brands)
            ->create();
    }
}
