<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Responses\ApiResponse;
use App\Services\Contact\ContactService;
use App\Services\Contact\DTO\ContactMessageDTO;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function send(ContactMessageRequest $request): JsonResponse
    {
        $dto = ContactMessageDTO::fromRequest($request);
        $message = $this->contactService->sendMessage($dto);

        return (new ApiResponse())
            ->setData($message)
            ->setCode(201)
            ->setMessages([__('messages.contact.sent_success')])
            ->create();
    }
}
