<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\Models\ContactMessage;
use App\Repositories\ContactMessageRepository;
use App\Services\Contact\DTO\ContactMessageDTO;

class ContactService
{
    public function __construct(protected ContactMessageRepository $contactMessageRepository)
    {
    }

    public function sendMessage(ContactMessageDTO $dto): ContactMessage
    {
        return $this->contactMessageRepository->create($dto->toArray());
    }
}
