<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\User\UserNotFoundException;
use App\Models\User;

/**
 * Handles all database interactions for the User model.
 */
class UserRepository
{
    public function __construct(protected User $model)
    {
    }

    /**
     * Find a user by column name and value.
     */
    public function findOneBy(string $column, mixed $value): ?User
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Find a user by column name and value or throw UserNotFoundException.
     *
     * @throws UserNotFoundException
     */
    public function findOneByOrFail(string $column, mixed $value): User
    {
        $user = $this->findOneBy($column, $value);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update a user's attributes.
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
