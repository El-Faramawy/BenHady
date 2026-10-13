<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Coupon\CouponDiscountTypeEnum;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Car;
use App\Models\City;
use App\Models\Coupon;
use App\Models\FuelType;
use App\Models\ModelYear;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CouponApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'status' => 'active',
            'phone_verified' => true,
        ]);

        $this->otherUser = User::factory()->create([
            'status' => 'active',
            'phone_verified' => true,
        ]);
    }

    public function test_apply_global_percentage_coupon_successfully(): void
    {
        Coupon::create([
            'code' => 'SAVE20',
            'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
            'discount_value' => 20,
            'is_global' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('coupons/apply', [
                'code' => 'SAVE20',
                'subtotal' => 500.00,
            ]);

        $response->assertStatus(200);
        $this->assertEquals(100, $response->json('data.discount_amount'));
        $this->assertEquals(400, $response->json('data.total'));
    }

    public function test_apply_fixed_amount_coupon_successfully(): void
    {
        Coupon::create([
            'code' => 'FLAT50',
            'discount_type' => CouponDiscountTypeEnum::FIXED->value,
            'discount_value' => 50,
            'is_global' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('coupons/apply', [
                'code' => 'FLAT50',
                'subtotal' => 300.00,
            ]);

        $response->assertStatus(200);
        $this->assertEquals(50, $response->json('data.discount_amount'));
        $this->assertEquals(250, $response->json('data.total'));
    }

    public function test_user_specific_coupon_succeeds_for_assigned_user_and_fails_for_others(): void
    {
        $coupon = Coupon::create([
            'code' => 'VIPONLY',
            'discount_type' => CouponDiscountTypeEnum::FIXED->value,
            'discount_value' => 100,
            'is_global' => false,
            'is_active' => true,
        ]);

        $coupon->users()->attach($this->user->id);

        // Assigned user succeeds
        $successResponse = $this->actingAs($this->user, 'api')
            ->postJson('coupons/apply', [
                'code' => 'VIPONLY',
                'subtotal' => 400.00,
            ]);

        $successResponse->assertStatus(200);
        $this->assertEquals(100, $successResponse->json('data.discount_amount'));

        // Unassigned user fails
        $failResponse = $this->actingAs($this->otherUser, 'api')
            ->postJson('coupons/apply', [
                'code' => 'VIPONLY',
                'subtotal' => 400.00,
            ]);

        $failResponse->assertStatus(422)
            ->assertJsonPath('code', 422);
    }

    public function test_apply_coupon_fails_when_expired(): void
    {
        Coupon::create([
            'code' => 'EXPIRED10',
            'discount_type' => CouponDiscountTypeEnum::FIXED->value,
            'discount_value' => 10,
            'is_global' => true,
            'expires_at' => Carbon::now()->subDay(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('coupons/apply', [
                'code' => 'EXPIRED10',
                'subtotal' => 200.00,
            ]);

        $response->assertStatus(422);
    }

    public function test_apply_coupon_fails_when_below_min_booking_amount(): void
    {
        Coupon::create([
            'code' => 'BIGBUY',
            'discount_type' => CouponDiscountTypeEnum::FIXED->value,
            'discount_value' => 50,
            'min_booking_amount' => 500.00,
            'is_global' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson('coupons/apply', [
                'code' => 'BIGBUY',
                'subtotal' => 300.00,
            ]);

        $response->assertStatus(422);
    }

    public function test_create_booking_with_coupon_applies_discount_and_records_usage(): void
    {
        $this->mockPresenceVerifier(1);

        $city = City::create(['name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'is_active' => true]);
        $branch = Branch::create([
            'city_id' => $city->id,
            'name_ar' => 'فرع المطار',
            'name_en' => 'Airport Branch',
            'address_ar' => 'مطار الملك خالد الدولي',
            'address_en' => 'King Khalid International Airport',
            'phone' => '0500000000',
            'status' => 'active',
        ]);

        foreach (['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day) {
            $branch->workingHours()->create([
                'day_of_week' => $day,
                'is_open' => true,
                'open_at' => '00:00:00',
                'close_at' => '23:59:59',
                'reservation_start_at' => '00:00:00',
                'reservation_close_at' => '23:59:59',
            ]);
        }

        $category = \App\Models\Category::create(['name_ar' => 'سيدان', 'name_en' => 'Sedan']);
        $brand = Brand::create(['name_ar' => 'تويوتا', 'name_en' => 'Toyota']);
        $modelYear = ModelYear::create(['year' => 2025]);
        $transmission = Transmission::create(['name_ar' => 'أوتوماتيك', 'name_en' => 'Automatic']);
        $fuelType = FuelType::create(['name_ar' => 'بنزين 91', 'name_en' => 'Gasoline 91']);

        $car = Car::create([
            'branch_id' => $branch->id,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_year_id' => $modelYear->id,
            'transmission_id' => $transmission->id,
            'fuel_type_id' => $fuelType->id,
            'name_ar' => 'تويوتا كامري 2025',
            'name_en' => 'Toyota Camry 2025',
            'daily_price' => 200.00,
            'status' => 'available',
            'seats' => 5,
        ]);

        Coupon::create([
            'code' => 'BOOK20',
            'discount_type' => CouponDiscountTypeEnum::PERCENTAGE->value,
            'discount_value' => 10, // 10%
            'is_global' => true,
            'is_active' => true,
        ]);

        $pickupAt = Carbon::now()->addDays(2)->format('Y-m-d H:i');
        $returnAt = Carbon::now()->addDays(5)->format('Y-m-d H:i'); // 3 days = 600 SAR subtotal

        $response = $this->actingAs($this->user, 'api')
            ->postJson('bookings', [
                'car_id' => $car->id,
                'pickup_branch_id' => $branch->id,
                'return_branch_id' => $branch->id,
                'pickup_at' => $pickupAt,
                'return_at' => $returnAt,
                'coupon_code' => 'BOOK20',
                'notes' => 'اختبار الحجز بكوبون',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.subtotal', '600.00')
            ->assertJsonPath('data.discount', '60.00')
            ->assertJsonPath('data.total', '540.00')
            ->assertJsonPath('data.coupon_code', 'BOOK20');

        $this->assertDatabaseHas('coupon_usages', [
            'user_id' => $this->user->id,
            'discount_amount' => 60.00,
        ]);
    }

    public function test_coupon_seeder_populates_coupons_correctly(): void
    {
        $this->seed(\Database\Seeders\CouponSeeder::class);

        $this->assertDatabaseHas('coupons', ['code' => 'BENHADY10', 'discount_value' => 10]);
        $this->assertDatabaseHas('coupons', ['code' => 'SAVE50', 'discount_value' => 50]);
        $this->assertDatabaseHas('coupons', ['code' => 'VIP2026', 'is_global' => false]);
        $this->assertDatabaseHas('coupons', ['code' => 'EXPIRED20', 'is_active' => true]);
    }
}
