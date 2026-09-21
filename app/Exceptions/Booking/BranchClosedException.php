<?php

declare(strict_types=1);

namespace App\Exceptions\Booking;

use App\Constants\Messages\BookingMessages;
use App\Exceptions\BusinessException;

class BranchClosedException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorKey(): string
    {
        return BookingMessages::BRANCH_CLOSED;
    }
}
