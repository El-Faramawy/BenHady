<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Notification;
use App\Services\Notification\DTO\NotificationFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function __construct(protected Notification $model)
    {
    }

    /**
     * @return LengthAwarePaginator<Notification>
     */
    public function getUserNotifications(int $userId, NotificationFilterDTO $dto): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->latest()
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page
            );
    }

    public function markAsReadForUser(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadNotificationsCount(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function deleteForUser(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->delete();
    }
}
