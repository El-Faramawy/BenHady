<?php

declare(strict_types=1);

namespace App\Exceptions\Car;

use App\Constants\Messages\CarMessages;
use App\Exceptions\BusinessException;

class CarNotFoundException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 404;
    }

    public function getErrorKey(): string
    {
        return CarMessages::CAR_NOT_FOUND;
    }
}
