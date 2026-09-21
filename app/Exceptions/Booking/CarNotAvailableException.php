<?php

declare(strict_types=1);

namespace App\Exceptions\Booking;

use App\Constants\Messages\BookingMessages;
use App\Exceptions\BusinessException;

class CarNotAvailableException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorKey(): string
    {
        return BookingMessages::CAR_NOT_AVAILABLE;
    }
}
