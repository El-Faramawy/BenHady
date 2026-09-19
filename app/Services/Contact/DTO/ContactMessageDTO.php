<?php

declare(strict_types=1);

namespace App\Services\Contact\DTO;

use App\Http\Requests\ContactMessageRequest;

readonly class ContactMessageDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $message,
        public ?string $bookingNumber = null,
        public ?int $userId = null,
    ) {
    }

    public static function fromRequest(ContactMessageRequest $request): self
    {
        return new self(
            name: (string) $request->input('name'),
            email: (string) $request->input('email'),
            subject: (string) $request->input('subject'),
            message: (string) $request->input('message'),
            bookingNumber: $request->filled('booking_number') ? (string) $request->input('booking_number') : null,
            userId: auth('api')->id() ? (int) auth('api')->id() : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'booking_number' => $this->bookingNumber,
            'user_id' => $this->userId,
        ];
    }
}
