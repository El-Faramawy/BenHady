<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Messages\UserMessages;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Responses\ApiResponse;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginUserDTO;
use App\Services\Auth\DTO\RegisterUserDTO;
use App\Services\Auth\DTO\VerifyPhoneDTO;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {
    }

    /**
     * Verify user phone number.
     */
    public function verifyPhone(VerifyPhoneRequest $request): JsonResponse
    {
        $dto = VerifyPhoneDTO::fromRequest($request);
        $this->authService->verifyPhone($dto);

        return (new ApiResponse())
            ->setMessages([UserMessages::phoneVerifiedSuccess()])
            ->create();
    }

    /**
     * Authenticate user by identifier (id_number or border_entry_number) and password.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginUserDTO::fromRequest($request);
        $data = $this->authService->loginUser($dto);

        return (new ApiResponse())
            ->setData($data)
            ->setMessages([UserMessages::loginSuccess()])
            ->create();
    }

    /**
     * Register a new user and return JWT token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterUserDTO::fromRequest($request);
        $data = $this->authService->registerUser($dto);

        return (new ApiResponse())
            ->setCode(Response::HTTP_CREATED)
            ->setData($data)
            ->setMessages([UserMessages::registerSuccess()])
            ->create();
    }

    /**
     * Invalidate the current JWT token.
     */
    public function logout(): JsonResponse
    {
        $this->authService->logoutUser();

        return (new ApiResponse())
            ->setMessages([UserMessages::logoutSuccess()])
            ->create();
    }
}
