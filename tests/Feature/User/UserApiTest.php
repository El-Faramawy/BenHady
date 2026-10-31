<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\PhoneToken;
use App\Models\User;
use App\Services\User\DTO\UpdateProfileDTO;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_update_profile_accepts_and_updates_city_id(): void
    {
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'John Doe',
        ]);

        $updatedUser = User::factory()->make([
            'id' => 1,
            'name' => 'John Doe',
            'city_id' => 1,
        ]);

        $this->actingAs($user, 'api');

        $mock = $this->mockService(UserService::class);
        $mock->shouldReceive('updateProfile')
            ->once()
            ->with(\Mockery::on(fn ($dto) => $dto instanceof UpdateProfileDTO && $dto->cityId === 1))
            ->andReturn($updatedUser);

        $this->mockPresenceVerifier(1);

        $response = $this->putJson('user/profile', [
            'city_id' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'city_id' => 1,
                ],
            ]);
    }

    public function test_update_profile_fails_validation_with_invalid_city_id(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $response = $this->putJson('user/profile', [
            'city_id' => 999999,
        ]);

        $response->assertStatus(422);
    }

    public function test_update_profile_accepts_city_id_via_multipart_put(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $updatedUser = User::factory()->make(['id' => 1, 'city_id' => 2]);
        $this->actingAs($user, 'api');

        $mock = $this->mockService(UserService::class);
        $mock->shouldReceive('updateProfile')
            ->once()
            ->with(\Mockery::on(fn ($dto) => $dto instanceof UpdateProfileDTO && $dto->cityId === 2))
            ->andReturn($updatedUser);

        $this->mockPresenceVerifier(1);

        $boundary = 'testboundary123';
        $body = "--{$boundary}\r\nContent-Disposition: form-data; name=\"city_id\"\r\n\r\n2\r\n--{$boundary}--\r\n";

        $response = $this->call(
            'PUT',
            'user/profile',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => "multipart/form-data; boundary={$boundary}",
                'HTTP_ACCEPT' => 'application/json',
            ],
            $body
        );

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'city_id' => 2,
                ],
            ]);
    }

    public function test_update_profile_accepts_post_method(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $updatedUser = User::factory()->make(['id' => 1, 'city_id' => 3]);
        $this->actingAs($user, 'api');

        $mock = $this->mockService(UserService::class);
        $mock->shouldReceive('updateProfile')
            ->once()
            ->with(\Mockery::on(fn ($dto) => $dto instanceof UpdateProfileDTO && $dto->cityId === 3))
            ->andReturn($updatedUser);

        $this->mockPresenceVerifier(1);

        $response = $this->postJson('user/profile', [
            'city_id' => 3,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'city_id' => 3,
                ],
            ]);
    }

    /**
     * Test DELETE auth/account endpoint returns success with mocked service.
     */
    public function test_delete_account_endpoint_returns_success_with_mocked_service(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $this->mock(UserService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteAccount')
                ->once();
        });

        $response = $this->deleteJson('auth/account');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.user.account_deleted_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test DELETE auth/account returns 401 when unauthenticated.
     */
    public function test_delete_account_endpoint_returns_401_when_unauthenticated(): void
    {
        $response = $this->deleteJson('auth/account');

        $response->assertStatus(401);
    }

    /**
     * Integration test: deleting account removes user record and associated phone tokens.
     */
    public function test_delete_account_integration_deletes_user_and_phone_tokens(): void
    {
        // 1. Create user and associated phone tokens in database
        $user = User::factory()->create([
            'phone' => '966500000099',
            'status' => 'active',
        ]);

        PhoneToken::create([
            'user_id' => $user->id,
            'phone_token' => 'device_token_abc_123',
        ]);
        PhoneToken::create([
            'user_id' => $user->id,
            'phone_token' => 'device_token_xyz_456',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertSame(2, PhoneToken::where('user_id', $user->id)->count());

        // 2. Perform delete account via authenticated request
        $token = auth()->login($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('auth/account');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.user.account_deleted_success'),
                ],
            ]);

        // 3. User and phone tokens should be removed from database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertSame(0, PhoneToken::where('user_id', $user->id)->count());
    }
}
