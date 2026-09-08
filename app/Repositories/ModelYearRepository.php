<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ModelYear;
use Illuminate\Database\Eloquent\Collection;

class ModelYearRepository
{
    public function __construct(protected ModelYear $model)
    {
    }

    /**
     * @return Collection<int, ModelYear>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('year', 'desc')
            ->get();
    }
}
