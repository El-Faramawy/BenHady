<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Feature\StoreFeatureRequest;
use App\Http\Requests\Admin\Feature\UpdateFeatureRequest;
use App\Models\Feature;
use App\Services\Feature\FeatureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends Controller
{
    public function __construct(protected FeatureService $featureService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->featureService->getDataTable();
        }

        return view('Admin.Feature.index');
    }

    public function create(): View
    {
        return view('Admin.Feature.parts.create');
    }

    public function store(StoreFeatureRequest $request): JsonResponse
    {
        $this->featureService->store(
            $request->validated(),
            $request->file('icon')
        );

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Feature $feature): View
    {
        return view('Admin.Feature.parts.edit', compact('feature'));
    }

    public function update(UpdateFeatureRequest $request, Feature $feature): JsonResponse
    {
        $this->featureService->update(
            $feature,
            $request->validated(),
            $request->file('icon')
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Feature $feature): JsonResponse
    {
        $this->featureService->delete($feature);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->featureService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
