<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    public function __construct(protected City $model)
    {
    }

    /**
     * @return Collection<int, City>
     */
    public function getAllActiveWithBranches(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->with(['branches' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
    }
}
