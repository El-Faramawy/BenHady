<?php

declare(strict_types=1);

namespace App\Services\Booking\DTO;

use App\Http\Requests\Booking\CreateBookingRequest;

readonly class CreateBookingDTO
{
    public function __construct(
        public int $userId,
        public int $carId,
        public int $pickupBranchId,
        public int $returnBranchId,
        public string $pickupAt,
        public string $returnAt,
        public ?string $notes = null,
    ) {
    }

    public static function fromRequest(CreateBookingRequest $request, int $userId): self
    {
        return new self(
            userId: $userId,
            carId: (int) $request->validated('car_id'),
            pickupBranchId: (int) $request->validated('pickup_branch_id'),
            returnBranchId: (int) $request->validated('return_branch_id'),
            pickupAt: (string) $request->validated('pickup_at'),
            returnAt: (string) $request->validated('return_at'),
            notes: $request->validated('notes'),
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'car_id' => $this->carId,
            'pickup_branch_id' => $this->pickupBranchId,
            'return_branch_id' => $this->returnBranchId,
            'pickup_at' => $this->pickupAt,
            'return_at' => $this->returnAt,
            'notes' => $this->notes,
        ];
    }
}
