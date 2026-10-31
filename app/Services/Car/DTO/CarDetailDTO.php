<?php

declare(strict_types=1);

namespace App\Services\Car\DTO;

use Illuminate\Http\Request;

readonly class CarDetailDTO
{
    public function __construct(
        public int $id,
        public ?int $cityId = null,
        public ?int $branchId = null,
    ) {
    }

    public static function fromRequest(int $id, Request $request): self
    {
        return new self(
            id: $id,
            cityId: $request->filled('city_id') ? (int) $request->input('city_id') : null,
            branchId: $request->filled('branch_id') ? (int) $request->input('branch_id') : null,
        );
    }
}
