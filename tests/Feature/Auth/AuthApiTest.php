<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Exceptions\Auth\AuthenticationFailedException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidVerificationCodeException;
use App\Exceptions\Auth\OtpResendCooldownException;
use App\Exceptions\Auth\PhoneNotVerifiedException;
use App\Exceptions\User\UserNotActiveException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginUserDTO;
use App\Services\Auth\DTO\RegisterStepOneDTO;
use App\Services\Auth\DTO\RegisterStepTwoDTO;
use App\Services\Auth\DTO\ResendPhoneOtpDTO;
use App\Services\Auth\DTO\VerifyPhoneDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test POST auth/login endpoint success path with Mockery.
     */
    public function test_login_endpoint_returns_success_with_mocked_service(): void
    {
        $mockAuthResponse = [
            'access_token' => 'mocked_jwt_token_string',
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '966500000001',
                'type' => 'customer',
                'status' => 'active',
            ],
        ];

        $this->mock(AuthService::class, function (MockInterface $mock) use ($mockAuthResponse) {
            $mock->shouldReceive('loginUser')
                ->once()
                ->withArgs(function (LoginUserDTO $dto) {
                    return $dto->identifier === '1020304050' && $dto->password === 'secret123';
                })
                ->andReturn($mockAuthResponse);
        });

        $response = $this->postJson('auth/login', [
            'identifier' => '1020304050',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockAuthResponse,
                'messages' => [
                    __('messages.user.login_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/login when service throws InvalidCredentialsException.
     */
    public function test_login_endpoint_returns_401_on_invalid_credentials(): void
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('loginUser')
                ->once()
                ->andThrow(new InvalidCredentialsException());
        });

        $response = $this->postJson('auth/login', [
            'identifier' => '1020304050',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'data' => null,
                'errors' => [__('messages.auth.invalid_credentials')],
                'messages' => [],
            ]);
    }

    /**
     * Test POST auth/login when service throws UserNotFoundException.
     */
    public function test_login_endpoint_returns_404_when_user_not_found(): void
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('loginUser')
                ->once()
                ->andThrow(new UserNotFoundException());
        });

        $response = $this->postJson('auth/login', [
            'identifier' => '0000000000',
            'password' => 'secret123',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'code' => 404,
                'data' => null,
                'errors' => [__('messages.user.not_found')],
                'messages' => [],
            ]);
    }

    /**
     * Test POST auth/login when service throws UserNotActiveException.
     */
    public function test_login_endpoint_returns_403_when_user_inactive(): void
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('loginUser')
                ->once()
                ->andThrow(new UserNotActiveException());
        });

        $response = $this->postJson('auth/login', [
            'identifier' => '1020304050',
            'password' => 'secret123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'data' => null,
                'errors' => [__('messages.user.not_active')],
                'messages' => [],
            ]);
    }

    /**
     * Test POST auth/login validation errors (missing required fields).
     */
    public function test_login_endpoint_fails_validation_without_data(): void
    {
        $response = $this->postJson('auth/login', []);

        $response->assertStatus(422)
            ->assertJsonPath('code', 422)
            ->assertJsonStructure(['code', 'message']);
    }

    /**
     * Test validation error messages are localized in Arabic when requesting Arabic locale.
     */
    public function test_validation_errors_are_localized_in_arabic(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('auth/login', []);

        $response->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'message' => 'حقل رقم الهوية / الإقامة / الحدود مطلوب.',
            ]);
    }

    /**
     * Test validation error messages are localized in English when requesting English locale.
     */
    public function test_validation_errors_are_localized_in_english(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'en'])
            ->postJson('auth/login', []);

        $response->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'message' => 'The identifier field is required.',
            ]);
    }

    /**
     * Test POST auth/verify-phone endpoint success path with Mockery.
     */
    public function test_verify_phone_endpoint_returns_success_with_mocked_service(): void
    {
        // Mock presence verifier so 'exists:users,phone' passes without database access
        $this->mockPresenceVerifier(1);

        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyPhone')
                ->once()
                ->withArgs(function (VerifyPhoneDTO $dto) {
                    return $dto->phone === '966588888888' && $dto->code === '1234';
                });
        });

        $response = $this->postJson('auth/verify-phone', [
            'phone' => '966588888888',
            'code' => '1234',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.user.phone_verified_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/verify-phone when service throws InvalidVerificationCodeException.
     */
    public function test_verify_phone_endpoint_returns_400_on_invalid_code(): void
    {
        // Mock presence verifier so 'exists:users,phone' passes without database access
        $this->mockPresenceVerifier(1);

        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyPhone')
                ->once()
                ->andThrow(new InvalidVerificationCodeException());
        });

        $response = $this->postJson('auth/verify-phone', [
            'phone' => '966588888888',
            'code' => '0000',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [__('messages.auth.invalid_verification_code')],
                'messages' => [],
            ]);
    }

    /**
     * Test POST auth/resend-phone-otp endpoint success path with Mockery.
     */
    public function test_resend_phone_otp_endpoint_returns_success_with_mocked_service(): void
    {
        $this->mockPresenceVerifier(1);

        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resendPhoneOtp')
                ->once()
                ->withArgs(function (ResendPhoneOtpDTO $dto) {
                    return $dto->phone === '966588888888';
                });
        });

        $response = $this->postJson('auth/resend-phone-otp', [
            'phone' => '966588888888',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.auth.otp_resent_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/resend-phone-otp when service throws OtpResendCooldownException.
     */
    public function test_resend_phone_otp_endpoint_returns_400_on_cooldown(): void
    {
        $this->mockPresenceVerifier(1);

        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resendPhoneOtp')
                ->once()
                ->andThrow(new OtpResendCooldownException());
        });

        $response = $this->postJson('auth/resend-phone-otp', [
            'phone' => '966588888888',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [__('messages.auth.otp_cooldown_error')],
                'messages' => [],
            ]);
    }

    /**
     * Test POST auth/resend-phone-otp fails validation when phone is missing.
     */
    public function test_resend_phone_otp_fails_validation_when_phone_missing(): void
    {
        $response = $this->postJson('auth/resend-phone-otp', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['code', 'message']);
    }

    /**
     * Test POST auth/logout endpoint success with Mockery.
     */
    public function test_logout_endpoint_returns_success_with_mocked_service(): void
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('logoutUser')
                ->once();
        });

        $user = User::factory()->make(['id' => 1]);
        $this->actingAs($user, 'api');

        $response = $this->postJson('auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.user.logout_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/logout endpoint when user is unauthenticated.
     */
    public function test_logout_endpoint_returns_401_when_unauthenticated(): void
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('logoutUser');
        });

        $response = $this->postJson('auth/logout');

        $response->assertStatus(401);
    }

    /**
     * Integration test: resend phone OTP updates DB, checks cooldown, and verify-phone clears code ensuring single use.
     */
    public function test_resend_otp_updates_db_and_verify_clears_code_ensuring_single_use(): void
    {
        // Restore real database presence verifier for this integration test
        app('validator')->setPresenceVerifier(new \Illuminate\Validation\DatabasePresenceVerifier(app('db')));

        // 1. Create user in database without verified phone
        $user = User::factory()->create([
            'phone' => '966509998877',
            'phone_verified' => false,
            'code' => null,
            'code_sent_at' => null,
        ]);

        // 2. Call resend-phone-otp
        $resendResponse = $this->postJson('auth/resend-phone-otp', [
            'phone' => '966509998877',
        ]);

        $resendResponse->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.auth.otp_resent_success'),
                ],
            ]);

        $user->refresh();
        $this->assertSame('1234', $user->code);
        $this->assertNotNull($user->code_sent_at);

        // 3. Immediately calling resend again should fail due to cooldown (400)
        $cooldownResponse = $this->postJson('auth/resend-phone-otp', [
            'phone' => '966509998877',
        ]);

        $cooldownResponse->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [
                    __('messages.auth.otp_cooldown_error'),
                ],
            ]);

        // 4. Verify phone with correct code
        $verifyResponse = $this->postJson('auth/verify-phone', [
            'phone' => '966509998877',
            'code' => '1234',
        ]);

        $verifyResponse->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.user.phone_verified_success'),
                ],
            ]);

        $user->refresh();
        $this->assertTrue((bool) $user->phone_verified);
        $this->assertNull($user->code);
        $this->assertNull($user->code_sent_at);

        // 5. Calling verify again with the same code must fail (single-use integrity)
        $replayResponse = $this->postJson('auth/verify-phone', [
            'phone' => '966509998877',
            'code' => '1234',
        ]);

        $replayResponse->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [
                    __('messages.auth.invalid_verification_code'),
                ],
            ]);
    }

    /**
     * Test POST auth/register-step-one success with Mockery.
     */
    public function test_register_step_one_endpoint_returns_success_with_mocked_service(): void
    {
        $mockData = [
            'id' => 1,
            'name' => 'Ahmed Samir',
            'email' => 'ahmed@example.com',
            'phone' => '966511112222',
            'date_of_birth' => '1995-05-15',
            'phone_verified' => false,
        ];

        $this->mock(AuthService::class, function (MockInterface $mock) use ($mockData) {
            $mock->shouldReceive('registerStepOne')
                ->once()
                ->withArgs(function (RegisterStepOneDTO $dto) {
                    return $dto->name === 'Ahmed Samir'
                        && $dto->email === 'ahmed@example.com'
                        && $dto->phone === '966511112222';
                })
                ->andReturn($mockData);
        });

        $response = $this->postJson('auth/register-step-one', [
            'name' => 'Ahmed Samir',
            'email' => 'ahmed@example.com',
            'phone' => '966511112222',
            'date_of_birth' => '1995-05-15',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockData,
                'messages' => [
                    __('messages.auth.register_step_one_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/register-step-one validation failure without data.
     */
    public function test_register_step_one_fails_validation_without_data(): void
    {
        $response = $this->postJson('auth/register-step-one', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['code', 'message']);
    }

    /**
     * Test POST auth/register-step-two success with Mockery.
     */
    public function test_register_step_two_endpoint_returns_created_with_mocked_service(): void
    {
        app('validator')->setPresenceVerifier(new \Illuminate\Validation\DatabasePresenceVerifier(app('db')));
        User::factory()->create(['phone' => '966511112222']);

        $mockAuthResponse = [
            'access_token' => 'jwt_token_for_step_two',
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => [
                'id' => 1,
                'name' => 'Ahmed Samir',
                'email' => 'ahmed@example.com',
                'phone' => '966511112222',
            ],
        ];

        $this->mock(AuthService::class, function (MockInterface $mock) use ($mockAuthResponse) {
            $mock->shouldReceive('registerStepTwo')
                ->once()
                ->withArgs(function (RegisterStepTwoDTO $dto) {
                    return $dto->phone === '966511112222'
                        && $dto->type === 'national_id'
                        && $dto->password === 'password123';
                })
                ->andReturn($mockAuthResponse);
        });

        $payload = [
            'phone' => '966511112222',
            'type' => 'national_id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'driving_license_number' => 'DL987654',
            'license_expiry_date' => '2030-01-01',
            'id_number' => '1020304050',
            'id_number_end_date' => '2030-01-01',
            'version_number' => 'v1',
        ];

        $response = $this->postJson('auth/register-step-two', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'code' => 201,
                'data' => $mockAuthResponse,
                'messages' => [
                    __('messages.user.register_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/register-step-two returns 400 when phone is not verified.
     */
    public function test_register_step_two_returns_400_when_phone_not_verified(): void
    {
        app('validator')->setPresenceVerifier(new \Illuminate\Validation\DatabasePresenceVerifier(app('db')));
        User::factory()->create(['phone' => '966511112222']);

        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('registerStepTwo')
                ->once()
                ->andThrow(new PhoneNotVerifiedException());
        });

        $payload = [
            'phone' => '966511112222',
            'type' => 'national_id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'driving_license_number' => 'DL987654',
            'license_expiry_date' => '2030-01-01',
            'id_number' => '1020304050',
            'id_number_end_date' => '2030-01-01',
            'version_number' => 'v1',
        ];

        $response = $this->postJson('auth/register-step-two', $payload);

        $response->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [
                    __('messages.auth.phone_not_verified'),
                ],
                'messages' => [],
            ]);
    }

    /**
     * Integration test: full 2-step register flow with non-unique email and phone verification guard.
     */
    public function test_two_step_registration_full_flow_with_database(): void
    {
        app('validator')->setPresenceVerifier(new \Illuminate\Validation\DatabasePresenceVerifier(app('db')));

        // 1. Step 1: Submit initial registration
        $stepOneResponse = $this->postJson('auth/register-step-one', [
            'name' => 'First User',
            'email' => 'shared@example.com',
            'phone' => '966577778888',
            'date_of_birth' => '1995-05-15',
        ]);

        $stepOneResponse->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'messages' => [
                    __('messages.auth.register_step_one_success'),
                ],
            ]);

        $user = User::where('phone', '966577778888')->first();
        $this->assertNotNull($user);
        $this->assertFalse((bool) $user->phone_verified);
        $this->assertSame('1234', $user->code);
        $this->assertNull($user->password);

        // 2. Non-unique email verification: Another user with SAME email can register step 1
        $secondUserResponse = $this->postJson('auth/register-step-one', [
            'name' => 'Second User',
            'email' => 'shared@example.com',
            'phone' => '966566665555',
            'date_of_birth' => '1998-08-20',
        ]);

        $secondUserResponse->assertStatus(200);
        $this->assertSame(2, User::where('email', 'shared@example.com')->count());

        // 3. Step 1 updates existing user with same phone
        $updateResponse = $this->postJson('auth/register-step-one', [
            'name' => 'First User Updated',
            'email' => 'shared@example.com',
            'phone' => '966577778888',
            'date_of_birth' => '1995-05-15',
        ]);

        $updateResponse->assertStatus(200);
        $user->refresh();
        $this->assertSame('First User Updated', $user->name);

        // 4. Step 2 fails before phone verification
        $stepTwoPayload = [
            'phone' => '966577778888',
            'type' => 'national_id',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'driving_license_number' => 'DL555444',
            'license_expiry_date' => '2032-01-01',
            'id_number' => '1099887766',
            'id_number_end_date' => '2032-01-01',
            'version_number' => 'v1',
        ];

        $failStepTwo = $this->postJson('auth/register-step-two', $stepTwoPayload);
        $failStepTwo->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'errors' => [
                    __('messages.auth.phone_not_verified'),
                ],
            ]);

        // 5. Verify phone with OTP code
        $verifyResponse = $this->postJson('auth/verify-phone', [
            'phone' => '966577778888',
            'code' => '1234',
        ]);
        $verifyResponse->assertStatus(200);

        $user->refresh();
        $this->assertTrue((bool) $user->phone_verified);
        $this->assertNull($user->code);

        // 6. Step 2 succeeds after phone verification
        $successStepTwo = $this->postJson('auth/register-step-two', $stepTwoPayload);
        $successStepTwo->assertStatus(201)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user',
                ],
                'messages',
                'errors',
            ]);

        $user->refresh();
        $this->assertSame('national_id', $user->type);
        $this->assertSame('active', $user->status);
        $this->assertNotNull($user->password);
    }
}
