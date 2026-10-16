<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\Faq\StoreFaqRequest;
use App\Http\Requests\Admin\Faq\UpdateFaqRequest;
use App\Models\Faq;
use App\Services\Faq\FaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(protected FaqService $faqService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->faqService->getDataTable();
        }

        return view('Admin.Faq.index');
    }

    public function create(): View
    {
        return view('Admin.Faq.parts.create');
    }

    public function store(StoreFaqRequest $request): JsonResponse
    {
        $this->faqService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Faq $faq): View
    {
        return view('Admin.Faq.parts.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq): JsonResponse
    {
        $this->faqService->update($faq, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $this->faqService->delete($faq);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->faqService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
