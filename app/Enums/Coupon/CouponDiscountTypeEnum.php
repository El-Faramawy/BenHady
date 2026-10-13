<?php

declare(strict_types=1);

namespace App\Enums\Coupon;

enum CouponDiscountTypeEnum: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';
}
