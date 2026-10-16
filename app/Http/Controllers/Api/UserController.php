<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\Messages\UserMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Responses\ApiResponse;
use App\Services\User\DTO\UpdateProfileDTO;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    /**
     * Get current authenticated user profile.
     */
    public function me(): JsonResponse
    {
        $user = $this->userService->getAuthenticatedUser();

        return (new ApiResponse())
            ->setData($user)
            ->create();
    }

    /**
     * Update current authenticated user profile.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $dto = UpdateProfileDTO::fromRequest($request);
        $user = $this->userService->updateProfile($dto);

        return (new ApiResponse())
            ->setData($user)
            ->setMessages([UserMessages::profileUpdateSuccess()])
            ->create();
    }

    /**
     * Delete current authenticated user account and all associated tokens.
     */
    public function deleteAccount(): JsonResponse
    {
        $this->userService->deleteAccount();

        return (new ApiResponse())
            ->setMessages([UserMessages::accountDeletedSuccess()])
            ->create();
    }
}
