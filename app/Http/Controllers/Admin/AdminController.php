<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Admin;
use App\Services\Admin\AdminService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->adminService->getDataTable();
        }

        return view('Admin.Admin.index');
    }

    public function create(): View
    {
        return view('Admin.Admin.parts.create');
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        $this->adminService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Admin $admin): View
    {
        return view('Admin.Admin.parts.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin): JsonResponse
    {
        $this->adminService->update($admin, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Admin $admin): JsonResponse
    {
        try {
            $this->adminService->delete($admin);

            return response()->json(['message' => __('messages.general.deleted_success')]);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->adminService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
