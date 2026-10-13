<?php

declare(strict_types=1);

namespace App\Constants\Messages;

class CouponMessages
{
    public const APPLIED = 'messages.coupon.applied';
    public const NOT_FOUND = 'messages.coupon.not_found';
    public const EXPIRED = 'messages.coupon.expired';
    public const NOT_STARTED = 'messages.coupon.not_started';
    public const LIMIT_REACHED = 'messages.coupon.limit_reached';
    public const USER_LIMIT_REACHED = 'messages.coupon.user_limit_reached';
    public const MIN_AMOUNT_NOT_MET = 'messages.coupon.min_amount_not_met';
    public const NOT_ELIGIBLE = 'messages.coupon.not_eligible';

    public static function applied(): string
    {
        return __(self::APPLIED);
    }
}
