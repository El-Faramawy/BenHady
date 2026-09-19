<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Messages\NotificationMessages;
use App\Http\Requests\DeletePhoneTokenRequest;
use App\Http\Requests\SavePhoneTokenRequest;
use App\Responses\ApiResponse;
use App\Services\PhoneToken\DTO\DeletePhoneTokenDTO;
use App\Services\PhoneToken\DTO\SavePhoneTokenDTO;
use App\Services\PhoneToken\PhoneTokenService;
use Illuminate\Http\JsonResponse;

class PhoneTokenController extends Controller
{
    public function __construct(
        protected PhoneTokenService $phoneTokenService,
    ) {
    }

    public function store(SavePhoneTokenRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();
        $dto = SavePhoneTokenDTO::fromRequest($request);

        $token = $this->phoneTokenService->saveToken($userId, $dto);

        return (new ApiResponse())
            ->setData($token)
            ->setMessages([NotificationMessages::tokenSavedSuccess()])
            ->create();
    }

    public function destroy(DeletePhoneTokenRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();
        $dto = DeletePhoneTokenDTO::fromRequest($request);

        $this->phoneTokenService->deleteToken($userId, $dto);

        return (new ApiResponse())
            ->setMessages([NotificationMessages::tokenDeletedSuccess()])
            ->create();
    }
}
