<?php

declare(strict_types=1);

namespace Tests\Feature\Notification;

use App\Models\PhoneToken;
use App\Models\User;
use App\Repositories\PhoneTokenRepository;
use App\Services\PhoneToken\PhoneTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_phone_token_returns_401_when_unauthenticated(): void
    {
        $response = $this->postJson('phone-tokens', ['token' => 'sample_fcm_token']);

        $response->assertStatus(401);
    }

    public function test_destroy_phone_token_returns_401_when_unauthenticated(): void
    {
        $response = $this->deleteJson('phone-tokens', ['token' => 'sample_fcm_token']);

        $response->assertStatus(401);
    }

    public function test_store_phone_token_validation_error_when_token_is_missing(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $response = $this->postJson('phone-tokens', []);

        $response->assertStatus(422);
    }

    public function test_store_phone_token_success(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $phoneToken = new PhoneToken([
            'user_id' => 1,
            'phone_token' => 'device_token_xyz_123',
        ]);
        $phoneToken->id = 1;

        $this->mockService(PhoneTokenService::class, [
            'saveToken' => $phoneToken,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('phone-tokens', ['phone_token' => 'device_token_xyz_123']);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'id' => 1,
                    'user_id' => 1,
                    'phone_token' => 'device_token_xyz_123',
                ],
                'messages' => [
                    __('messages.notification.token_saved_success'),
                ],
                'errors' => [],
            ]);
    }

    public function test_store_phone_token_success_with_english_locale(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $phoneToken = new PhoneToken([
            'user_id' => 1,
            'phone_token' => 'device_token_xyz_456',
        ]);
        $phoneToken->id = 2;

        $this->mockService(PhoneTokenService::class, [
            'saveToken' => $phoneToken,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'en'])
            ->postJson('phone-tokens', ['phone_token' => 'device_token_xyz_456']);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'id' => 2,
                    'user_id' => 1,
                    'phone_token' => 'device_token_xyz_456',
                ],
                'messages' => [
                    'Token saved successfully',
                ],
            ]);
    }

    public function test_destroy_phone_token_success(): void
    {
        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $this->mockService(PhoneTokenService::class, [
            'deleteToken' => 1,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->deleteJson('phone-tokens', ['phone_token' => 'device_token_xyz_123']);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.notification.token_deleted_success'),
                ],
                'errors' => [],
            ]);
    }

    public function test_repository_creates_and_updates_token_for_same_user(): void
    {
        $user = User::factory()->create();

        $repo = app(PhoneTokenRepository::class);

        // 1. Create token
        $saved = $repo->saveToken($user->id, 'device_fcm_token_1');
        $this->assertSame($user->id, $saved->user_id);
        $this->assertSame('device_fcm_token_1', $saved->phone_token);
        $this->assertDatabaseCount('phone_tokens', 1);

        // 2. Re-saving same token for same user updates and does not duplicate
        $updated = $repo->saveToken($user->id, 'device_fcm_token_1');
        $this->assertSame($saved->id, $updated->id);
        $this->assertDatabaseCount('phone_tokens', 1);

        // 3. User can have multiple devices (e.g. tablet)
        $secondToken = $repo->saveToken($user->id, 'device_fcm_token_2');
        $this->assertDatabaseCount('phone_tokens', 2);
        $userTokens = $repo->getTokensByUserId($user->id);
        $this->assertCount(2, $userTokens);

        // 4. If another user logs in on device 1, token 1 is reassigned to user 2
        $user2 = User::factory()->create();
        $reassigned = $repo->saveToken($user2->id, 'device_fcm_token_1');
        $this->assertSame($user2->id, $reassigned->user_id);
        $this->assertDatabaseCount('phone_tokens', 2);

        // 5. Delete token for user 2
        $deleted = $repo->deleteToken('device_fcm_token_1', $user2->id);
        $this->assertSame(1, $deleted);
        $this->assertDatabaseCount('phone_tokens', 1);
        $this->assertDatabaseMissing('phone_tokens', ['phone_token' => 'device_fcm_token_1']);
    }
}
