<?php

declare(strict_types=1);

namespace App\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse extends Response
{
    /**
     * Messages.
     *
     * @var array
     */
    public array $messages = [];

    /**
     * Build the JSON response.
     */
    public function create(): JsonResponse
    {
        $response = new JsonResponse(
            [
                'code' => $this->getCode(),
                'errors' => $this->getErrors(),
                'data' => $this->getData(),
                'messages' => $this->getMessages()
            ],
            $this->getCode()
        );

        $this->reset();

        return $response;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Reset the response state to avoid pollution between calls.
     */
    private function reset(): void
    {
        $this->messages = [];
    }
}
