<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\User\UserStatusEnum;
use App\Exceptions\Auth\AuthenticationFailedException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidVerificationCodeException;
use App\Exceptions\User\UserNotActiveException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Auth\DTO\LoginUserDTO;
use App\Services\Auth\DTO\RegisterUserDTO;
use App\Services\Auth\DTO\VerifyPhoneDTO;
use Illuminate\Support\Facades\Hash;

/**
 * Handles all authentication business logic: login, register, logout.
 */
class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * Verify user phone number.
     *
     * @throws UserNotFoundException
     * @throws InvalidVerificationCodeException
     */
    public function verifyPhone(VerifyPhoneDTO $dto): void
    {
        $user = $this->userRepository->findOneByOrFail('phone', $dto->phone);

        $condition = config('services.sms.condition');

        if ($condition === 'test') {
            $testValue = config('services.sms.test_value');
            if ($dto->code !== $testValue) {
                throw new InvalidVerificationCodeException();
            }
        } else {
            // todo connect with oursms
        }

        $this->userRepository->update($user, ['phone_verified' => true]);
    }

    /**
     * Authenticate a user by identifier (id_number or border_entry_number) and password.
     *
     * @throws UserNotFoundException
     * @throws InvalidCredentialsException
     * @throws UserNotActiveException
     */
    public function loginUser(LoginUserDTO $dto): array
    {
        $user = $this->resolveUserByIdentifier($dto->identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if ($user->status !== UserStatusEnum::ACTIVE->value) {
            throw new UserNotActiveException();
        }

        $token = auth()->login($user);

        return $this->buildAuthResponse($token, $user);
    }

    /**
     * Register a new user and return token data.
     */
    public function registerUser(RegisterUserDTO $dto): array
    {
        $data = $dto->toArray();
        $data['password'] = Hash::make($dto->password);
        $data['status'] = UserStatusEnum::ACTIVE->value;

        $user = $this->userRepository->create($data);

        $token = auth()->login($user);

        return $this->buildAuthResponse($token, $user);
    }

    /**
     * Invalidate the current user's JWT token.
     *
     * @throws AuthenticationFailedException
     */
    public function logoutUser(): void
    {
        if (!auth()->check()) {
            throw new AuthenticationFailedException();
        }

        auth()->logout();
    }

    /**
     * Attempt to find a user by id_number first, then by border_entry_number.
     */
    private function resolveUserByIdentifier(string $identifier): ?User
    {
        return $this->userRepository->findOneBy('id_number', $identifier)
            ?? $this->userRepository->findOneBy('border_entry_number', $identifier);
    }

    /**
     * Build the standardized auth response with token and user data.
     */
    private function buildAuthResponse(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user->only([
                'id',
                'name',
                'email',
                'phone',
                'date_of_birth',
                'type',
                'status',
            ]),
        ];
    }
}
