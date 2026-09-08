<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use App\Exceptions\BusinessException;

/**
 * Thrown when a user's account is not in active status.
 */
class UserNotActiveException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 403;
    }

    public function getErrorKey(): string
    {
        return 'messages.user.not_active';
    }
}
