<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class LoginUserDTO
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $password,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('identifier'),
            $request->input('password'),
        );
    }
}
