<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\Auth\AuthenticationFailedException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\User\DTO\UpdateProfileDTO;
use Illuminate\Support\Facades\Hash;

/**
 * Handles user-related business logic (profile, account management).
 * Does NOT interact with the database directly — uses UserRepository.
 */
class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * Get the currently authenticated user.
     *
     * @throws AuthenticationFailedException
     */
    public function getAuthenticatedUser(): User
    {
        if (!auth()->check()) {
            throw new AuthenticationFailedException();
        }

        return $this->userRepository->findOneByOrFail('id', (int) auth()->user()->id);
    }

    /**
     * Update the currently authenticated user's profile.
     *
     * @throws AuthenticationFailedException
     */
    public function updateProfile(UpdateProfileDTO $dto): User
    {
        $user = $this->getAuthenticatedUser();

        $data = $dto->toArray();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }
}
