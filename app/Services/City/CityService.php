<?php

declare(strict_types=1);

namespace App\Services\City;

use App\Repositories\CityRepository;
use Illuminate\Database\Eloquent\Collection;

class CityService
{
    public function __construct(protected CityRepository $cityRepository)
    {
    }

    public function getCities(): Collection
    {
        return $this->cityRepository->getAllActiveWithBranches();
    }
}
