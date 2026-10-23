<?php

declare(strict_types=1);

namespace Tests\Feature\Booking;

use App\Models\Booking;
use App\Models\User;
use App\Services\Booking\BookingService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->make([
            'id' => 1,
            'status' => 'active',
            'phone_verified' => true,
        ]);
    }

    public function test_create_booking_successfully(): void
    {
        $this->mockPresenceVerifier(1);

        $mockBooking = new Booking();
        $mockBooking->id = 1;
        $mockBooking->booking_number = 'BH-2026-00001';
        $mockBooking->status = 'pending';

        $this->mockService(BookingService::class, [
            'createBooking' => $mockBooking,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('bookings', [
                'car_id' => 1,
                'pickup_branch_id' => 1,
                'return_branch_id' => 1,
                'pickup_at' => now()->addDay()->format('Y-m-d H:i:s'),
                'return_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
                'notes' => 'Test booking',
            ]);

        $response->assertStatus(201);
        $this->assertEquals(201, $response->json('code'));
    }

    public function test_get_active_bookings(): void
    {
        $this->mockService(BookingService::class, [
            'getActiveBookings' => new Collection([]),
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->getJson('bookings/active');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('code'));
    }

    public function test_get_closed_bookings(): void
    {
        $this->mockService(BookingService::class, [
            'getClosedBookings' => new Collection([]),
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->getJson('bookings/closed');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('code'));
    }

    public function test_get_booking_details(): void
    {
        $mockBooking = new Booking();
        $mockBooking->id = 5;
        $mockBooking->status = 'pending';

        $this->mockService(BookingService::class, [
            'getBookingDetails' => $mockBooking,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->getJson('bookings/5');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('code'));
    }

    public function test_complete_booking_successfully(): void
    {
        $mockBooking = new Booking();
        $mockBooking->id = 5;
        $mockBooking->status = 'completed';

        $this->mockService(BookingService::class, [
            'completeBooking' => $mockBooking,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('bookings/5/complete');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('code'));
    }
}
