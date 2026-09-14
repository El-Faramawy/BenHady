<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Messages\NotificationMessages;
use App\Http\Requests\NotificationFilterRequest;
use App\Responses\ApiResponse;
use App\Services\Notification\DTO\NotificationFilterDTO;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index(NotificationFilterRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();
        $dto = NotificationFilterDTO::fromRequest($request);

        $notifications = $this->notificationService->getUserNotifications($userId, $dto);

        return (new ApiResponse())
            ->setData($notifications)
            ->create();
    }

    public function destroy(): JsonResponse
    {
        $userId = (int) auth()->id();
        $this->notificationService->deleteForUser($userId);

        return (new ApiResponse())
            ->setMessages([NotificationMessages::deleteSuccess()])
            ->create();
    }

    public function unreadCount(): JsonResponse
    {
        $userId = (int) auth()->id();
        $count = $this->notificationService->getUnreadNotificationsCount($userId);

        return (new ApiResponse())
            ->setData(['unread_count' => $count])
            ->create();
    }
}
