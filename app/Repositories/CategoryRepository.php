<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function __construct(protected Category $model)
    {
    }

    /**
     * @return Collection<int, Category>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
