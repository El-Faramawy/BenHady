<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

/**
 * Thrown when the provided phone verification code is incorrect.
 */
class InvalidVerificationCodeException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.invalid_verification_code';
    }
}
