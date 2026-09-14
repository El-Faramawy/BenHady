<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Branch\DayOfWeekEnum;
use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\City;
use App\Models\FuelType;
use App\Models\ModelYear;
use App\Models\Transmission;
use App\Services\Home\DTO\HomeFilterDTO;
use App\Services\Home\HomeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_endpoint_returns_success_with_aggregated_data(): void
    {
        $mockData = [
            'categories' => [
                ['id' => 1, 'name_ar' => 'اقتصادية', 'name_en' => 'Economy'],
            ],
            'featured_cars' => [
                ['id' => 1, 'name_en' => 'Toyota Camry 2025', 'daily_price' => 220],
            ],
            'handpicked_cars' => [
                ['id' => 2, 'name_en' => 'Toyota Yaris 2024', 'daily_price' => 130],
            ],
        ];

        $mock = $this->mockService(HomeService::class);
        $mock->shouldReceive('getHomeData')
            ->once()
            ->with(Mockery::on(function (HomeFilterDTO $dto) {
                return $dto->cityId === null && $dto->pickupDate === null && $dto->pickupTime === null;
            }))
            ->andReturn($mockData);

        $response = $this->getJson('home');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockData,
                'errors' => [],
            ]);
    }

    public function test_home_endpoint_accepts_city_and_pickup_date_and_time_filters(): void
    {
        $this->mockPresenceVerifier(1);

        $mockData = [
            'categories' => [],
            'featured_cars' => [
                ['id' => 1, 'name_en' => 'Toyota Camry 2025', 'daily_price' => 220],
            ],
            'handpicked_cars' => [
                ['id' => 1, 'name_en' => 'Toyota Camry 2025', 'daily_price' => 220],
            ],
        ];

        $mock = $this->mockService(HomeService::class);
        $mock->shouldReceive('getHomeData')
            ->once()
            ->with(Mockery::on(function (HomeFilterDTO $dto) {
                return $dto->cityId === 1
                    && $dto->pickupDate === '2026-05-22'
                    && $dto->pickupTime === '14:00';
            }))
            ->andReturn($mockData);

        $response = $this->getJson('home?city_id=1&pickup_date=2026-05-22&pickup_time=14:00');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockData,
                'errors' => [],
            ]);
    }

    public function test_home_endpoint_validation_fails_with_invalid_date(): void
    {
        $response = $this->getJson('home?pickup_date=invalid-date');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'code',
                'errors',
            ]);
    }

    public function test_home_endpoint_validation_fails_with_invalid_time(): void
    {
        $response = $this->getJson('home?pickup_time=25:70');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'code',
                'errors',
            ]);
    }

    public function test_home_endpoint_validation_fails_with_nonexistent_city(): void
    {
        $response = $this->getJson('home?city_id=99999');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'code',
                'errors',
            ]);
    }

    public function test_home_endpoint_validation_messages_are_localized_in_arabic(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('home?city_id=99999');

        $response->assertStatus(422);
        $this->assertStringContainsString('المدينة', (string) $response->json('errors.0'));
    }

    public function test_home_service_filters_cars_by_city_and_branch_working_hours(): void
    {
        $cityRiyadh = City::create(['name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'is_active' => true]);
        $cityJeddah = City::create(['name_ar' => 'جدة', 'name_en' => 'Jeddah', 'is_active' => true]);

        // Branch 1: Airport branch in Riyadh, open 24/7 every day including Friday
        $airportBranch = Branch::create([
            'city_id' => $cityRiyadh->id,
            'name_ar' => 'فرع المطار',
            'name_en' => 'Airport Branch',
            'address_ar' => 'المطار',
            'address_en' => 'Airport',
            'is_active' => true,
        ]);
        BranchWorkingHour::create([
            'branch_id' => $airportBranch->id,
            'day_of_week' => DayOfWeekEnum::FRIDAY,
            'is_open' => true,
            'is_24_hours' => true,
        ]);

        // Branch 2: City branch in Riyadh, Friday is OFF
        $cityBranch = Branch::create([
            'city_id' => $cityRiyadh->id,
            'name_ar' => 'فرع العليا',
            'name_en' => 'Olaya Branch',
            'address_ar' => 'العليا',
            'address_en' => 'Olaya',
            'is_active' => true,
        ]);
        BranchWorkingHour::create([
            'branch_id' => $cityBranch->id,
            'day_of_week' => DayOfWeekEnum::FRIDAY,
            'is_open' => false,
            'open_at' => null,
            'close_at' => null,
            'is_24_hours' => false,
        ]);

        // Branch 3: Jeddah branch
        $jeddahBranch = Branch::create([
            'city_id' => $cityJeddah->id,
            'name_ar' => 'فرع جدة',
            'name_en' => 'Jeddah Branch',
            'address_ar' => 'جدة',
            'address_en' => 'Jeddah',
            'is_active' => true,
        ]);
        BranchWorkingHour::create([
            'branch_id' => $jeddahBranch->id,
            'day_of_week' => DayOfWeekEnum::FRIDAY,
            'is_open' => true,
            'is_24_hours' => true,
        ]);

        $category = Category::create(['name_ar' => 'اقتصادية', 'name_en' => 'Economy', 'is_active' => true]);
        $brand = Brand::create(['name_ar' => 'تويوتا', 'name_en' => 'Toyota', 'is_active' => true]);
        $modelYear = ModelYear::create(['year' => 2025, 'is_active' => true]);
        $transmission = Transmission::create(['name_ar' => 'أوتوماتيك', 'name_en' => 'Automatic', 'is_active' => true]);
        $fuelType = FuelType::create(['name_ar' => 'بنزين', 'name_en' => 'Petrol', 'is_active' => true]);

        // Car 1 in Airport Branch (Riyadh)
        $carAirport = Car::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'branch_id' => $airportBranch->id,
            'model_year_id' => $modelYear->id,
            'transmission_id' => $transmission->id,
            'fuel_type_id' => $fuelType->id,
            'name_ar' => 'كامري مطار',
            'name_en' => 'Camry Airport',
            'daily_price' => 200,
            'is_featured' => true,
            'is_handpicked' => true,
            'is_active' => true,
            'status' => 'available',
        ]);

        // Car 2 in Olaya Branch (Riyadh - closed on Friday)
        $carCity = Car::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'branch_id' => $cityBranch->id,
            'model_year_id' => $modelYear->id,
            'transmission_id' => $transmission->id,
            'fuel_type_id' => $fuelType->id,
            'name_ar' => 'يارس العليا',
            'name_en' => 'Yaris Olaya',
            'daily_price' => 120,
            'is_featured' => true,
            'is_handpicked' => true,
            'is_active' => true,
            'status' => 'available',
        ]);

        // Car 3 in Jeddah Branch
        $carJeddah = Car::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'branch_id' => $jeddahBranch->id,
            'model_year_id' => $modelYear->id,
            'transmission_id' => $transmission->id,
            'fuel_type_id' => $fuelType->id,
            'name_ar' => 'كورولا جدة',
            'name_en' => 'Corolla Jeddah',
            'daily_price' => 150,
            'is_featured' => true,
            'is_handpicked' => true,
            'is_active' => true,
            'status' => 'available',
        ]);

        // Real HomeService instance call
        /** @var HomeService $homeService */
        $homeService = app(HomeService::class);

        // 2026-05-22 is a Friday
        $filter = new HomeFilterDTO(
            cityId: $cityRiyadh->id,
            pickupDate: '2026-05-22',
            pickupTime: '10:00'
        );

        $result = $homeService->getHomeData($filter);

        // Only Car 1 (Airport branch in Riyadh) should be returned, NOT Car 2 (closed Friday) and NOT Car 3 (Jeddah)
        $featuredCarIds = collect($result['featured_cars'])->pluck('id')->all();
        $this->assertContains($carAirport->id, $featuredCarIds);
        $this->assertNotContains($carCity->id, $featuredCarIds);
        $this->assertNotContains($carJeddah->id, $featuredCarIds);

        $handpickedCarIds = collect($result['handpicked_cars'])->pluck('id')->all();
        $this->assertContains($carAirport->id, $handpickedCarIds);
        $this->assertNotContains($carCity->id, $handpickedCarIds);
        $this->assertNotContains($carJeddah->id, $handpickedCarIds);
    }
}
