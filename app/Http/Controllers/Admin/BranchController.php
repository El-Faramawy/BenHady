<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Branch\StoreBranchRequest;
use App\Http\Requests\Admin\Branch\UpdateBranchRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Branch;
use App\Models\City;
use App\Services\Branch\BranchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function __construct(protected BranchService $branchService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->branchService->getDataTable();
        }

        return view('Admin.Branch.index');
    }

    public function create(): View
    {
        $cities = City::where('is_active', true)->get();

        return view('Admin.Branch.parts.create', compact('cities'));
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        $this->branchService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Branch $branch): View
    {
        $cities = City::where('is_active', true)->get();

        return view('Admin.Branch.parts.edit', compact('branch', 'cities'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        $this->branchService->update($branch, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $this->branchService->delete($branch);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->branchService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
