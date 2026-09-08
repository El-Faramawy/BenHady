<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class VerifyPhoneDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $code,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('phone'),
            $request->input('code'),
        );
    }
}
