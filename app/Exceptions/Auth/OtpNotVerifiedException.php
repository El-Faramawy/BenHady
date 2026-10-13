<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class OtpNotVerifiedException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.otp_not_verified';
    }
}
