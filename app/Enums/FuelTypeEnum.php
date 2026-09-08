<?php

declare(strict_types=1);

namespace App\Enums;

enum FuelTypeEnum: string
{
    case GASOLINE = 'gasoline';
    case DIESEL = 'diesel';
    case ELECTRIC = 'electric';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::GASOLINE => __('messages.fuel_type.gasoline'),
            self::DIESEL => __('messages.fuel_type.diesel'),
            self::ELECTRIC => __('messages.fuel_type.electric'),
            self::HYBRID => __('messages.fuel_type.hybrid'),
        };
    }
}
