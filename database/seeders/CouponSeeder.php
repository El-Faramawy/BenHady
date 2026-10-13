<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Coupon\CouponDiscountTypeEnum;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'BENHADY10',
                'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
                'discount_value' => 10.00,
                'max_discount_amount' => 150.00,
                'min_booking_amount' => 100.00,
                'is_global' => true,
                'usage_limit' => 1000,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'start_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE50',
                'discount_type' => CouponDiscountTypeEnum::FIXED->value,
                'discount_value' => 50.00,
                'max_discount_amount' => null,
                'min_booking_amount' => 250.00,
                'is_global' => true,
                'usage_limit' => 500,
                'usage_limit_per_user' => 2,
                'used_count' => 0,
                'start_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'NATIONAL15',
                'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
                'discount_value' => 15.00,
                'max_discount_amount' => 200.00,
                'min_booking_amount' => 200.00,
                'is_global' => true,
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'start_at' => Carbon::now()->subDays(2),
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'VIP2026',
                'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
                'discount_value' => 20.00,
                'max_discount_amount' => 300.00,
                'min_booking_amount' => 400.00,
                'is_global' => false,
                'usage_limit' => 50,
                'usage_limit_per_user' => 3,
                'used_count' => 0,
                'start_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED20',
                'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
                'discount_value' => 20.00,
                'max_discount_amount' => 100.00,
                'min_booking_amount' => 100.00,
                'is_global' => true,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'used_count' => 0,
                'start_at' => Carbon::now()->subMonths(3),
                'expires_at' => Carbon::now()->subMonth(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $data) {
            $coupon = Coupon::firstOrCreate(
                ['code' => $data['code']],
                $data
            );

            // Assign the VIP coupon to the first user if any exists
            if (!$data['is_global'] && $data['code'] === 'VIP2026') {
                $firstUser = User::first();
                if ($firstUser) {
                    $coupon->users()->syncWithoutDetaching([$firstUser->id]);
                }
            }
        }
    }
}
