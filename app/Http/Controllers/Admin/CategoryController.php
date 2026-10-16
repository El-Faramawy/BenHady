<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->categoryService->getDataTable();
        }

        return view('Admin.Category.index');
    }

    public function create(): View
    {
        return view('Admin.Category.parts.create');
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->categoryService->store(
            $request->validated(),
            $request->file('icon')
        );

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function edit(Category $category): View
    {
        return view('Admin.Category.parts.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $this->categoryService->update(
            $category,
            $request->validated(),
            $request->file('icon')
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->categoryService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
