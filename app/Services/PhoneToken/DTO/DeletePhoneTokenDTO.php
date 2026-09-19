<?php

declare(strict_types=1);

namespace App\Services\PhoneToken\DTO;

use Illuminate\Http\Request;

readonly class DeletePhoneTokenDTO
{
    public function __construct(
        public string $token,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            token: (string) $request->input('phone_token')
        );
    }
}
