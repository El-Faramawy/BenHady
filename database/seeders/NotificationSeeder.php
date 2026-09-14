<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        Notification::firstOrCreate(
            [
                'user_id' => $user->id,
                'title_ar' => 'تأكيد الحجز',
            ],
            [
                'booking_id' => 1,
                'title_en' => 'Booking Confirmation',
                'body_ar' => 'تم تأكيد حجزك بنجاح للسيارة مرسيدس E-Class.',
                'body_en' => 'Your booking for Mercedes E-Class has been confirmed successfully.',
                'type' => 'booking',
            ]
        );

        Notification::firstOrCreate(
            [
                'user_id' => $user->id,
                'title_ar' => 'شحن المحفظة',
            ],
            [
                'booking_id' => null,
                'title_en' => 'Wallet Recharge',
                'body_ar' => 'تم شحن محفظتك بمبلغ 500 ريال بنجاح.',
                'body_en' => 'Your wallet has been recharged with 500 SAR successfully.',
                'type' => 'wallet',
            ]
        );
    }
}
