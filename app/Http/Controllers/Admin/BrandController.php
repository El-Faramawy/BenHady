<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Brand;
use App\Services\Brand\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brandService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->brandService->getDataTable();
        }

        return view('Admin.Brand.index');
    }

    public function create(): View
    {
        return view('Admin.Brand.parts.create');
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $this->brandService->store(
            $request->validated(),
            $request->file('logo')
        );

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Brand $brand): View
    {
        return view('Admin.Brand.parts.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $this->brandService->update(
            $brand,
            $request->validated(),
            $request->file('logo')
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $this->brandService->delete($brand);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->brandService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
