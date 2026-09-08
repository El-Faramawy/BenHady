<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Exceptions\Auth\AuthenticationFailedException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidVerificationCodeException;
use App\Exceptions\User\UserNotActiveException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginUserDTO;
use App\Services\Auth\DTO\RegisterUserDTO;
use App\Services\Auth\DTO\VerifyPhoneDTO;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
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
     * Test POST auth/register endpoint success path with Mockery.
     */
    public function test_register_endpoint_returns_created_status_with_mocked_service(): void
    {
        $mockRegisterResponse = [
            'access_token' => 'mocked_registered_token',
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => [
                'id' => 1,
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'phone' => '966599999999',
                'type' => 'national_id',
                'status' => 'active',
            ],
        ];

        $this->mock(AuthService::class, function (MockInterface $mock) use ($mockRegisterResponse) {
            $mock->shouldReceive('registerUser')
                ->once()
                ->withArgs(function (RegisterUserDTO $dto) {
                    return $dto->name === 'New User'
                        && $dto->email === 'newuser@example.com'
                        && $dto->phone === '966599999999';
                })
                ->andReturn($mockRegisterResponse);
        });

        $payload = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'phone' => '966599999999',
            'date_of_birth' => '1995-05-15',
            'type' => 'national_id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'driving_license_number' => 'DL123456',
            'license_expiry_date' => '2030-01-01',
            'id_number' => '1020304050',
            'id_number_end_date' => '2030-01-01',
            'version_number' => 'v1',
        ];

        $response = $this->postJson('auth/register', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'code' => 201,
                'data' => $mockRegisterResponse,
                'messages' => [
                    __('messages.user.register_success'),
                ],
                'errors' => [],
            ]);
    }

    /**
     * Test POST auth/register validation errors.
     */
    public function test_register_endpoint_fails_validation_without_required_fields(): void
    {
        $response = $this->postJson('auth/register', []);

        $response->assertStatus(422)
            ->assertJsonPath('code', 422)
            ->assertJsonStructure(['code', 'message']);
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
}
