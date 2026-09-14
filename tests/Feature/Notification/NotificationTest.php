<?php

declare(strict_types=1);

namespace Tests\Feature\Notification;

use App\Models\Notification;
use App\Models\User;
use App\Repositories\NotificationRepository;
use App\Services\Notification\DTO\NotificationFilterDTO;
use App\Services\Notification\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_index_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('notifications');

        $response->assertStatus(401);
    }

    public function test_notifications_index_returns_paginated_notifications_when_authenticated(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $notification = new Notification([
            'id' => 1,
            'user_id' => 1,
            'booking_id' => 10,
            'title_ar' => 'تأكيد الحجز',
            'title_en' => 'Booking Confirmation',
            'body_ar' => 'تم تأكيد الحجز',
            'body_en' => 'Booking Confirmed',
            'type' => 'booking',
            'is_read' => true,
        ]);
        $notification->id = 1;

        $paginator = new LengthAwarePaginator(
            items: collect([$notification]),
            total: 1,
            perPage: 10,
            currentPage: 2
        );

        $this->mockService(NotificationService::class, [
            'getUserNotifications' => $paginator,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('notifications?page=2&per_page=10');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'current_page' => 2,
                    'per_page' => 10,
                    'data' => [
                        [
                            'id' => 1,
                            'title' => 'تأكيد الحجز',
                            'booking_id' => 10,
                            'type' => 'booking',
                            'is_read' => true,
                        ],
                    ],
                ],
            ]);
    }

    public function test_notifications_index_accepts_limit_parameter(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $paginator = new LengthAwarePaginator(
            items: collect([]),
            total: 0,
            perPage: 5,
            currentPage: 1
        );

        $this->mockService(NotificationService::class, [
            'getUserNotifications' => $paginator,
        ]);

        $response = $this->getJson('notifications?limit=5');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'per_page' => 5,
                ],
            ]);
    }

    public function test_unread_count_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('notifications/unread-count');

        $response->assertStatus(401);
    }

    public function test_unread_count_returns_count_when_authenticated(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $this->mockService(NotificationService::class, [
            'getUnreadNotificationsCount' => 3,
        ]);

        $response = $this->getJson('notifications/unread-count');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'unread_count' => 3,
                ],
            ]);
    }

    public function test_delete_notification_returns_401_when_unauthenticated(): void
    {
        $response = $this->deleteJson('notifications');

        $response->assertStatus(401);
    }

    public function test_delete_notifications_returns_success(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $this->mockService(NotificationService::class, [
            'deleteForUser' => 2,
        ]);

        $response = $this->deleteJson('notifications');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.notification.delete_success'),
                ],
            ]);
    }

    public function test_repository_marks_notifications_as_read_when_fetching(): void
    {
        $user = User::factory()->create();

        $n1 = Notification::create([
            'user_id' => $user->id,
            'title_ar' => 'تنبيه 1',
            'title_en' => 'Notice 1',
            'body_ar' => 'تفاصيل 1',
            'body_en' => 'Details 1',
            'type' => 'booking',
            'is_read' => false,
        ]);

        $n2 = Notification::create([
            'user_id' => $user->id,
            'title_ar' => 'تنبيه 2',
            'title_en' => 'Notice 2',
            'body_ar' => 'تفاصيل 2',
            'body_en' => 'Details 2',
            'type' => 'wallet',
            'is_read' => false,
        ]);

        $repo = app(NotificationRepository::class);

        $this->assertSame(2, $repo->getUnreadNotificationsCount($user->id));

        $dto = new NotificationFilterDTO(perPage: 10, page: 1);
        $paginator = $repo->getUserNotifications($user->id, $dto);
        $this->assertCount(2, $paginator->items());

        // Verify markAsReadForUser
        $this->assertSame(2, $repo->getUnreadNotificationsCount($user->id));
        $repo->markAsReadForUser($user->id);
        $this->assertSame(0, $repo->getUnreadNotificationsCount($user->id));
        $this->assertTrue((bool) $n1->fresh()->is_read);
        $this->assertTrue((bool) $n2->fresh()->is_read);

        // Verify deleteForUser deletes all user's notifications
        $deletedCount = $repo->deleteForUser($user->id);
        $this->assertSame(2, $deletedCount);
        $this->assertDatabaseCount('notifications', 0);
    }
}
