<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getCategories();

        return (new ApiResponse())
            ->setData($categories)
            ->create();
    }
}
