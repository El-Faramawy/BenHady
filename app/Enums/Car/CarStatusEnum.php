<?php

declare(strict_types=1);

namespace App\Enums\Car;

enum CarStatusEnum: string
{
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case MAINTENANCE = 'maintenance';
    case RESERVED = 'reserved';

    public function labelAr(): string
    {
        return match ($this) {
            self::AVAILABLE => 'متاحة للإيجار',
            self::RENTED => 'مؤجرة حالياً',
            self::MAINTENANCE => 'في الصيانة',
            self::RESERVED => 'محجوزة',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::RENTED => 'Rented',
            self::MAINTENANCE => 'Maintenance',
            self::RESERVED => 'Reserved',
        };
    }
}
