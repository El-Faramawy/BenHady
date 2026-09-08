<?php

declare(strict_types=1);

namespace App\Services\Home;

use App\Repositories\BrandRepository;
use App\Repositories\CarRepository;
use App\Repositories\CategoryRepository;
use App\Services\Home\DTO\HomeFilterDTO;

class HomeService
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected BrandRepository $brandRepository,
        protected CarRepository $carRepository,
    ) {
    }

    public function getHomeData(?HomeFilterDTO $filter = null): array
    {
        return [
            'categories' => $this->categoryRepository->getAllActive(),
            'brands' => $this->brandRepository->getAllActive(),
            'featured_cars' => $this->carRepository->getFeaturedCars($filter, 6),
            'handpicked_cars' => $this->carRepository->getHandpickedCars($filter, 6),
        ];
    }
}
