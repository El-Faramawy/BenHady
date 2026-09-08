<?php

declare(strict_types=1);

namespace App\Enums;

enum CarStatusEnum: string
{
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => __('messages.car_status.available'),
            self::RENTED => __('messages.car_status.rented'),
            self::MAINTENANCE => __('messages.car_status.maintenance'),
        };
    }
}
