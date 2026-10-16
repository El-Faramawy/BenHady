<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\FuelType\StoreFuelTypeRequest;
use App\Http\Requests\Admin\FuelType\UpdateFuelTypeRequest;
use App\Models\FuelType;
use App\Services\FuelType\FuelTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuelTypeController extends Controller
{
    public function __construct(protected FuelTypeService $fuelTypeService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->fuelTypeService->getDataTable();
        }

        return view('Admin.FuelType.index');
    }

    public function create(): View
    {
        return view('Admin.FuelType.parts.create');
    }

    public function store(StoreFuelTypeRequest $request): JsonResponse
    {
        $this->fuelTypeService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(FuelType $fuelType): View
    {
        return view('Admin.FuelType.parts.edit', compact('fuelType'));
    }

    public function update(UpdateFuelTypeRequest $request, FuelType $fuelType): JsonResponse
    {
        $this->fuelTypeService->update($fuelType, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(FuelType $fuelType): JsonResponse
    {
        $this->fuelTypeService->delete($fuelType);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->fuelTypeService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
