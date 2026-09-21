<?php

declare(strict_types=1);

namespace App\Constants\Messages;

class BookingMessages
{
    public const CREATED = 'messages.booking.created';
    public const NOT_FOUND = 'messages.booking.not_found';
    public const CAR_NOT_AVAILABLE = 'messages.booking.car_not_available';
    public const BRANCH_CLOSED = 'messages.booking.branch_closed';
    public const OVERLAP = 'messages.booking.overlap';

    public static function created(): string
    {
        return __(self::CREATED);
    }
}
