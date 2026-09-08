<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

/**
 * Thrown when authentication fails (invalid credentials, unauthenticated access).
 */
class AuthenticationFailedException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 401;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.unauthenticated';
    }
}
