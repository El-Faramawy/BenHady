<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

/**
 * Thrown when provided credentials (identifier + password) do not match any user record.
 */
class InvalidCredentialsException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 401;
    }

    public function getErrorKey(): string
    {
        return 'messages.auth.invalid_credentials';
    }
}
