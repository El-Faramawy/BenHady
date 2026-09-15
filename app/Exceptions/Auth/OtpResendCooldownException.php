<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

/**
 * Thrown when resending verification code is requested before cooldown expires.
 */
class OtpResendCooldownException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.otp_cooldown_error';
    }
}
