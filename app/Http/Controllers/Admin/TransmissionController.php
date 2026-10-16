<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Transmission\StoreTransmissionRequest;
use App\Http\Requests\Admin\Transmission\UpdateTransmissionRequest;
use App\Models\Transmission;
use App\Services\Transmission\TransmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransmissionController extends Controller
{
    public function __construct(protected TransmissionService $transmissionService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->transmissionService->getDataTable();
        }

        return view('Admin.Transmission.index');
    }

    public function create(): View
    {
        return view('Admin.Transmission.parts.create');
    }

    public function store(StoreTransmissionRequest $request): JsonResponse
    {
        $this->transmissionService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Transmission $transmission): View
    {
        return view('Admin.Transmission.parts.edit', compact('transmission'));
    }

    public function update(UpdateTransmissionRequest $request, Transmission $transmission): JsonResponse
    {
        $this->transmissionService->update($transmission, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Transmission $transmission): JsonResponse
    {
        $this->transmissionService->delete($transmission);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->transmissionService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
