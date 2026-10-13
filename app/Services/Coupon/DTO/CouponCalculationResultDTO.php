<?php

declare(strict_types=1);

namespace App\Services\Coupon\DTO;

use App\Models\Coupon;
use BackedEnum;

readonly class CouponCalculationResultDTO
{
    public function __construct(
        public Coupon $coupon,
        public float $discountAmount,
        public float $finalTotal,
        public float $subtotal
    ) {
    }

    public function toArray(): array
    {
        $discountType = $this->coupon->discount_type instanceof BackedEnum
            ? $this->coupon->discount_type->value
            : (string) $this->coupon->discount_type;

        return [
            'coupon_id' => $this->coupon->id,
            'coupon_code' => $this->coupon->code,
            'discount_type' => $discountType,
            'discount_value' => (float) $this->coupon->discount_value,
            'subtotal' => round($this->subtotal, 2),
            'discount_amount' => round($this->discountAmount, 2),
            'total' => round($this->finalTotal, 2),
        ];
    }
}
