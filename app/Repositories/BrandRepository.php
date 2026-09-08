<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository
{
    public function __construct(protected Brand $model)
    {
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
