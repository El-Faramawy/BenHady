<?php

declare(strict_types=1);

namespace App\Services\Brand;

use App\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\Collection;

class BrandService
{
    public function __construct(protected BrandRepository $brandRepository)
    {
    }

    public function getBrands(): Collection
    {
        return $this->brandRepository->getAllActive();
    }
}
