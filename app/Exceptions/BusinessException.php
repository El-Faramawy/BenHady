<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Base exception for all business-related errors.
 * All custom exceptions should extend this class.
 * The global exception handler will render these as consistent API responses.
 */
abstract class BusinessException extends RuntimeException
{
    /**
     * Get the HTTP status code for this exception.
     */
    abstract public function getStatusCode(): int;

    /**
     * Get the translation key for the error message.
     */
    abstract public function getErrorKey(): string;
}
