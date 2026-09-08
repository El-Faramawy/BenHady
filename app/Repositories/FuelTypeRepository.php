<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FuelType;
use Illuminate\Database\Eloquent\Collection;

class FuelTypeRepository
{
    public function __construct(protected FuelType $model)
    {
    }

    /**
     * @return Collection<int, FuelType>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
