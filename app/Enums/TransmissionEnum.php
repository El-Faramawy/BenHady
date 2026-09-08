<?php

declare(strict_types=1);

namespace App\Enums;

enum TransmissionEnum: string
{
    case AUTOMATIC = 'automatic';
    case MANUAL = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::AUTOMATIC => __('messages.transmission.automatic'),
            self::MANUAL => __('messages.transmission.manual'),
        };
    }
}
