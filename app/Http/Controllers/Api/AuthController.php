<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\Messages\UserMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterStepOneRequest;
use App\Http\Requests\Auth\RegisterStepTwoRequest;
use App\Http\Requests\Auth\ResendPhoneOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyPhoneRequest;
use App\Responses\ApiResponse;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginUserDTO;
use App\Services\Auth\DTO\RegisterStepOneDTO;
use App\Services\Auth\DTO\RegisterStepTwoDTO;
use App\Services\Auth\DTO\ResendPhoneOtpDTO;
use App\Services\Auth\DTO\ResetPasswordDTO;
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
        $data = $this->authService->verifyPhone($dto);

        return (new ApiResponse())
            ->setData($data)
            ->setMessages([UserMessages::phoneVerifiedSuccess()])
            ->create();
    }

    /**
     * Reset user password.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $dto = ResetPasswordDTO::fromRequest($request);
        $this->authService->resetPassword($dto);

        return (new ApiResponse())
            ->setMessages([UserMessages::passwordResetSuccess()])
            ->create();
    }

    /**
     * Resend phone verification OTP code.
     */
    public function resendPhoneOtp(ResendPhoneOtpRequest $request): JsonResponse
    {
        $dto = ResendPhoneOtpDTO::fromRequest($request);
        $this->authService->resendPhoneOtp($dto);

        return (new ApiResponse())
            ->setMessages([__('messages.auth.otp_resent_success')])
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
     * Register Step 1: Save basic info and dispatch phone OTP.
     */
    public function registerStepOne(RegisterStepOneRequest $request): JsonResponse
    {
        $dto = RegisterStepOneDTO::fromRequest($request);
        $data = $this->authService->registerStepOne($dto);

        return (new ApiResponse())
            ->setData($data)
            ->setMessages([__('messages.auth.register_step_one_success')])
            ->create();
    }

    /**
     * Register Step 2: Complete registration with password and identity details.
     */
    public function registerStepTwo(RegisterStepTwoRequest $request): JsonResponse
    {
        $dto = RegisterStepTwoDTO::fromRequest($request);
        $data = $this->authService->registerStepTwo($dto);

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
