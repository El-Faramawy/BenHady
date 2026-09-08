<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transmission;
use Illuminate\Database\Eloquent\Collection;

class TransmissionRepository
{
    public function __construct(protected Transmission $model)
    {
    }

    /**
     * @return Collection<int, Transmission>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
