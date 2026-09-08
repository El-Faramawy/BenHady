<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use App\Exceptions\BusinessException;

/**
 * Thrown when a user cannot be found in the database.
 */
class UserNotFoundException extends BusinessException
{
    public function getStatusCode(): int
    {
        return 404;
    }

    public function getErrorKey(): string
    {
        return 'messages.user.not_found';
    }
}
