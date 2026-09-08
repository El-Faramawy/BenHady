<?php

declare(strict_types=1);

namespace App\Services\Car\DTO;

use Illuminate\Http\Request;

readonly class CarFilterDTO
{
    public function __construct(
        public ?int $categoryId = null,
        public ?int $brandId = null,
        public ?int $cityId = null,
        public ?int $branchId = null,
        public ?int $modelYearId = null,
        public ?int $transmissionId = null,
        public ?int $fuelTypeId = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?int $seats = null,
        public ?string $search = null,
        public ?string $sortBy = null,
        public string $sortOrder = 'asc',
        public int $perPage = 15,
        public int $page = 1,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            categoryId: $request->filled('category_id') ? (int) $request->input('category_id') : null,
            brandId: $request->filled('brand_id') ? (int) $request->input('brand_id') : null,
            cityId: $request->filled('city_id') ? (int) $request->input('city_id') : null,
            branchId: $request->filled('branch_id') ? (int) $request->input('branch_id') : null,
            modelYearId: $request->filled('model_year_id') ? (int) $request->input('model_year_id') : null,
            transmissionId: $request->filled('transmission_id') ? (int) $request->input('transmission_id') : null,
            fuelTypeId: $request->filled('fuel_type_id') ? (int) $request->input('fuel_type_id') : null,
            minPrice: $request->filled('min_price') ? (float) $request->input('min_price') : null,
            maxPrice: $request->filled('max_price') ? (float) $request->input('max_price') : null,
            seats: $request->filled('seats') ? (int) $request->input('seats') : null,
            search: $request->filled('search') ? (string) $request->input('search') : null,
            sortBy: $request->filled('sort_by') ? (string) $request->input('sort_by') : null,
            sortOrder: $request->input('sort_order', 'asc') === 'desc' ? 'desc' : 'asc',
            perPage: min(max((int) $request->input('per_page', 15), 1), 50),
            page: max((int) $request->input('page', 1), 1),
        );
    }
}
