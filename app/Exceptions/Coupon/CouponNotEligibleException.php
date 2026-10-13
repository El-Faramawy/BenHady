<?php

declare(strict_types=1);

namespace App\Exceptions\Coupon;

use App\Constants\Messages\CouponMessages;
use App\Exceptions\BusinessException;

class CouponNotEligibleException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorKey(): string
    {
        return CouponMessages::NOT_ELIGIBLE;
    }
}
