<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Services\Notification\DTO\NotificationFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function __construct(protected NotificationRepository $notificationRepository)
    {
    }

    /**
     * @return LengthAwarePaginator<Notification>
     */
    public function getUserNotifications(int $userId, NotificationFilterDTO $dto): LengthAwarePaginator
    {
        $notifications = $this->notificationRepository->getUserNotifications($userId, $dto);

        $this->notificationRepository->markAsReadForUser($userId);

        return $notifications;
    }

    public function getUnreadNotificationsCount(int $userId): int
    {
        return $this->notificationRepository->getUnreadNotificationsCount($userId);
    }

    public function deleteForUser(int $userId): int
    {
        return $this->notificationRepository->deleteForUser($userId);
    }
}
