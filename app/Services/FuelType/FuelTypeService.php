<?php

declare(strict_types=1);

namespace App\Services\FuelType;

use App\Repositories\FuelTypeRepository;
use Illuminate\Database\Eloquent\Collection;

class FuelTypeService
{
    public function __construct(protected FuelTypeRepository $fuelTypeRepository)
    {
    }

    public function getFuelTypes(): Collection
    {
        return $this->fuelTypeRepository->getAllActive();
    }
}
