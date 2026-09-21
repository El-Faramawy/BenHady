<?php

declare(strict_types=1);

namespace App\Enums\Booking;

enum BookingStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function labelAr(): string
    {
        return match ($this) {
            self::PENDING => 'قيد الانتظار',
            self::ACTIVE => 'نشطة',
            self::COMPLETED => 'مكتمل',
            self::CANCELLED => 'ملغي',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function label(): string
    {
        return app()->getLocale() === 'ar' ? $this->labelAr() : $this->labelEn();
    }
}
