<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class ResetPasswordDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $password,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            (string) $request->input('phone'),
            (string) $request->input('password'),
        );
    }
}
