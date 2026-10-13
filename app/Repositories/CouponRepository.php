<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\CouponUsage;

class CouponRepository
{
    public function __construct(
        protected Coupon $model,
        protected CouponUsage $couponUsageModel
    ) {
    }

    public function findByCode(string $code): ?Coupon
    {
        return $this->model
            ->where('code', strtoupper(trim($code)))
            ->first();
    }

    public function findById(int $id): ?Coupon
    {
        return $this->model->find($id);
    }

    public function getUserUsageCount(int $couponId, int $userId): int
    {
        return $this->couponUsageModel
            ->where('coupon_id', $couponId)
            ->where('user_id', $userId)
            ->count();
    }

    public function isUserAssigned(int $couponId, int $userId): bool
    {
        $coupon = $this->findById($couponId);
        if (!$coupon) {
            return false;
        }

        return $coupon->users()->where('users.id', $userId)->exists();
    }

    public function recordUsage(int $couponId, int $userId, int $bookingId, float $discountAmount): CouponUsage
    {
        return $this->couponUsageModel->create([
            'coupon_id' => $couponId,
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'discount_amount' => $discountAmount,
        ]);
    }

    public function incrementUsedCount(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
