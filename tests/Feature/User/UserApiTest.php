<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\User\DTO\UpdateProfileDTO;
use App\Services\User\UserService;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public function test_me_endpoint_returns_user_data_when_authenticated(): void
    {
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->actingAs($user, 'api');

        $this->mockService(UserService::class, [
            'getAuthenticatedUser' => $user,
        ]);

        $response = $this->getJson('auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'id' => 1,
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                ],
                'errors' => [],
            ]);
    }

    public function test_me_endpoint_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('auth/me');

        $response->assertStatus(401);
    }

    public function test_update_profile_endpoint_returns_success_with_mocked_service(): void
    {
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $updatedUser = User::factory()->make([
            'id' => 1,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->actingAs($user, 'api');

        $this->mockService(UserService::class, [
            'updateProfile' => $updatedUser,
        ]);

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson('user/profile', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                ],
                'messages' => [
                    __('messages.user.profile_update_success'),
                ],
                'errors' => [],
            ]);
    }

    public function test_update_profile_fails_validation_with_invalid_email(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $response = $this->putJson('user/profile', [
            'email' => 'invalid-email-format',
        ]);

        $response->assertStatus(422);
    }
}
