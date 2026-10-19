<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RentalPolicy\UpdateRentalPolicyRequest;
use App\Models\RentalPolicy;
use App\Services\Policy\PolicyService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RentalPolicyController extends Controller
{
    public function __construct(protected PolicyService $policyService)
    {
    }

    public function index(): View
    {
        $policy = $this->policyService->getPolicy();

        return view('Admin.RentalPolicy.index', compact('policy'));
    }

    public function update(UpdateRentalPolicyRequest $request): JsonResponse
    {
        $policy = $this->policyService->getPolicy();

        $this->policyService->updatePolicy($policy, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }
}
