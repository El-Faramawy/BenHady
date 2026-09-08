<?php

declare(strict_types=1);

namespace App\Services\Category;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAllActive();
    }
}
