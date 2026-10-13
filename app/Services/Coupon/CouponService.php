<?php

declare(strict_types=1);

namespace App\Services\Coupon;

use App\Enums\Coupon\CouponDiscountTypeEnum;
use App\Exceptions\Coupon\CouponExpiredException;
use App\Exceptions\Coupon\CouponLimitReachedException;
use App\Exceptions\Coupon\CouponMinAmountException;
use App\Exceptions\Coupon\CouponNotEligibleException;
use App\Exceptions\Coupon\CouponNotStartedException;
use App\Exceptions\Coupon\CouponUserLimitReachedException;
use App\Exceptions\Coupon\InvalidCouponException;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use App\Services\Coupon\DTO\CouponCalculationResultDTO;
use Illuminate\Support\Carbon;

class CouponService
{
    public function __construct(protected CouponRepository $couponRepository)
    {
    }

    /**
     * Validate a coupon code for a given user and subtotal, returning discount details.
     *
     * @throws InvalidCouponException
     * @throws CouponNotStartedException
     * @throws CouponExpiredException
     * @throws CouponNotEligibleException
     * @throws CouponMinAmountException
     * @throws CouponLimitReachedException
     * @throws CouponUserLimitReachedException
     */
    public function validateAndCalculate(string $code, float $subtotal, int $userId): CouponCalculationResultDTO
    {
        $coupon = $this->couponRepository->findByCode($code);

        if (!$coupon || !$coupon->is_active) {
            throw new InvalidCouponException();
        }

        $now = Carbon::now();

        if ($coupon->start_at && $now->lt($coupon->start_at)) {
            throw new CouponNotStartedException();
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            throw new CouponExpiredException();
        }

        if (!$coupon->is_global) {
            $isAssigned = $this->couponRepository->isUserAssigned($coupon->id, $userId);
            if (!$isAssigned) {
                throw new CouponNotEligibleException();
            }
        }

        if ($coupon->min_booking_amount !== null && $subtotal < (float) $coupon->min_booking_amount) {
            throw new CouponMinAmountException();
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw new CouponLimitReachedException();
        }

        $userUsageCount = $this->couponRepository->getUserUsageCount($coupon->id, $userId);
        if ($userUsageCount >= $coupon->usage_limit_per_user) {
            throw new CouponUserLimitReachedException();
        }

        $discountType = $coupon->discount_type instanceof CouponDiscountTypeEnum
            ? $coupon->discount_type->value
            : (string) $coupon->discount_type;

        $discountValue = (float) $coupon->discount_value;

        if ($discountType === CouponDiscountTypeEnum::PERCENTAGE->value) {
            $rawDiscount = ($subtotal * $discountValue) / 100;
            if ($coupon->max_discount_amount !== null) {
                $discountAmount = min($rawDiscount, (float) $coupon->max_discount_amount);
            } else {
                $discountAmount = $rawDiscount;
            }
        } else {
            $discountAmount = min($subtotal, $discountValue);
        }

        $discountAmount = round($discountAmount, 2);
        $finalTotal = max(0.00, round($subtotal - $discountAmount, 2));

        return new CouponCalculationResultDTO(
            coupon: $coupon,
            discountAmount: $discountAmount,
            finalTotal: $finalTotal,
            subtotal: $subtotal
        );
    }

    public function recordUsage(Coupon $coupon, int $userId, int $bookingId, float $discountAmount): void
    {
        $this->couponRepository->recordUsage($coupon->id, $userId, $bookingId, $discountAmount);
        $this->couponRepository->incrementUsedCount($coupon);
    }
}
