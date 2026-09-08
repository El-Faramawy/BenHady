<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\Policy\PolicyService;
use Illuminate\Http\JsonResponse;

class PolicyController extends Controller
{
    public function __construct(protected PolicyService $policyService)
    {
    }

    public function index(): JsonResponse
    {
        $policy = $this->policyService->getPolicies();

        return (new ApiResponse())
            ->setData($policy)
            ->create();
    }
}
