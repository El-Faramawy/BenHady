<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Delete a user.
     */
    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    /**
     * Delete multiple users by their IDs.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Get DataTable query builder for admin management.
     */
    public function getDataTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Get user with their bookings and coupons loaded.
     */
    public function getUserWithBookings(User $user): User
    {
        return $user->load(['bookings.car.brand', 'bookings.pickupBranch', 'bookings.returnBranch', 'coupons']);
    }

    /**
     * Get total count of users.
     */
    public function getTotalCount(): int
    {
        return $this->model->newQuery()->count();
    }

    /**
     * Get count of active/verified users.
     */
    public function getActiveCount(): int
    {
        return $this->model->newQuery()->where('status', 'active')->count();
    }

    /**
     * Get recent registered users.
     *
     * @return Collection<int, User>
     */
    public function getRecentUsers(int $limit = 5): Collection
    {
        return $this->model->newQuery()->latest('id')->take($limit)->get();
    }
}
