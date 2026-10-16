<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\ModelYear\StoreModelYearRequest;
use App\Http\Requests\Admin\ModelYear\UpdateModelYearRequest;
use App\Models\ModelYear;
use App\Services\ModelYear\ModelYearService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModelYearController extends Controller
{
    public function __construct(protected ModelYearService $modelYearService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->modelYearService->getDataTable();
        }

        return view('Admin.ModelYear.index');
    }

    public function create(): View
    {
        return view('Admin.ModelYear.parts.create');
    }

    public function store(StoreModelYearRequest $request): JsonResponse
    {
        $this->modelYearService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(ModelYear $modelYear): View
    {
        return view('Admin.ModelYear.parts.edit', compact('modelYear'));
    }

    public function update(UpdateModelYearRequest $request, ModelYear $modelYear): JsonResponse
    {
        $this->modelYearService->update($modelYear, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(ModelYear $modelYear): JsonResponse
    {
        $this->modelYearService->delete($modelYear);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->modelYearService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
