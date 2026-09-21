<?php

declare(strict_types=1);

namespace App\Exceptions\Booking;

use App\Constants\Messages\BookingMessages;
use App\Exceptions\BusinessException;

class BookingNotFoundException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 404;
    }

    public function getErrorKey(): string
    {
        return BookingMessages::NOT_FOUND;
    }
}
