<?php

declare(strict_types=1);

namespace App\Services\Home\DTO;

use App\Http\Requests\Home\HomeRequest;

readonly class HomeFilterDTO
{
    public function __construct(
        public ?int $cityId = null,
        public ?string $pickupDate = null,
        public ?string $pickupTime = null,
    ) {
    }

    public static function fromRequest(HomeRequest $request): self
    {
        return new self(
            cityId: $request->filled('city_id') ? (int) $request->input('city_id') : null,
            pickupDate: $request->filled('pickup_date') ? (string) $request->input('pickup_date') : null,
            pickupTime: $request->filled('pickup_time') ? (string) $request->input('pickup_time') : null,
        );
    }

    public function hasFilters(): bool
    {
        return $this->cityId !== null || $this->pickupDate !== null || $this->pickupTime !== null;
    }

    public function toArray(): array
    {
        return [
            'city_id' => $this->cityId,
            'pickup_date' => $this->pickupDate,
            'pickup_time' => $this->pickupTime,
        ];
    }
}
