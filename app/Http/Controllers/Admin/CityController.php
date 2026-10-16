<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\City\StoreCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\City;
use App\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function __construct(protected CityService $cityService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->cityService->getDataTable();
        }

        return view('Admin.City.index');
    }

    public function create(): View
    {
        return view('Admin.City.parts.create');
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $this->cityService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(City $city): View
    {
        return view('Admin.City.parts.edit', compact('city'));
    }

    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        $this->cityService->update($city, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(City $city): JsonResponse
    {
        $this->cityService->delete($city);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->cityService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
