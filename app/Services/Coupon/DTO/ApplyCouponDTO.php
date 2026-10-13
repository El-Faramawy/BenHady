<?php

declare(strict_types=1);

namespace App\Services\Coupon\DTO;

use Illuminate\Http\Request;

readonly class ApplyCouponDTO
{
    public function __construct(
        public string $code,
        public float $subtotal,
        public int $userId
    ) {
    }

    public static function fromRequest(Request $request, int $userId): self
    {
        return new self(
            code: (string) $request->input('code'),
            subtotal: (float) $request->input('subtotal', 0),
            userId: $userId
        );
    }
}
