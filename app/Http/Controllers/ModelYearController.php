<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\ModelYear\ModelYearService;
use Illuminate\Http\JsonResponse;

class ModelYearController extends Controller
{
    public function __construct(protected ModelYearService $modelYearService)
    {
    }

    public function index(): JsonResponse
    {
        $years = $this->modelYearService->getModelYears();

        return (new ApiResponse())
            ->setData($years)
            ->create();
    }
}
