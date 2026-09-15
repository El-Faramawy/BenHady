<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

/**
 * Thrown when trying to complete registration before phone verification.
 */
class PhoneNotVerifiedException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.phone_not_verified';
    }
}
