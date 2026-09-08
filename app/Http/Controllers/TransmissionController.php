<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\Transmission\TransmissionService;
use Illuminate\Http\JsonResponse;

class TransmissionController extends Controller
{
    public function __construct(protected TransmissionService $transmissionService)
    {
    }

    public function index(): JsonResponse
    {
        $transmissions = $this->transmissionService->getTransmissions();

        return (new ApiResponse())
            ->setData($transmissions)
            ->create();
    }
}
