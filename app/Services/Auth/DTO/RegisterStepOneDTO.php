<?php

declare(strict_types=1);

namespace App\Services\Auth\DTO;

use Illuminate\Http\Request;

class RegisterStepOneDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $dateOfBirth,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            (string) $request->input('name'),
            (string) $request->input('email'),
            (string) $request->input('phone'),
            (string) $request->input('date_of_birth'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth,
        ];
    }
}
