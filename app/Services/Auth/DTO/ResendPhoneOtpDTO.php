<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class ResendPhoneOtpDTO
{
    public function __construct(
        public readonly string $phone,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            (string) $request->input('phone'),
        );
    }
}
