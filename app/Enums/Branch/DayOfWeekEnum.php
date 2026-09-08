<?php

declare(strict_types=1);

namespace App\Enums\Branch;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

enum DayOfWeekEnum: string
{
    case SUNDAY = 'sunday';
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';

    public static function fromDate(string|CarbonInterface $date): self
    {
        $carbon = is_string($date) ? Carbon::parse($date) : $date;
        $dayName = strtolower($carbon->format('l'));

        return self::from($dayName);
    }

    public function label(): string
    {
        return __('messages.days.' . $this->value);
    }
}
