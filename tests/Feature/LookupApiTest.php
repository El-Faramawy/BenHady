<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Brand\BrandService;
use App\Services\Category\CategoryService;
use App\Services\City\CityService;
use App\Services\FuelType\FuelTypeService;
use App\Services\ModelYear\ModelYearService;
use App\Services\Policy\PolicyService;
use App\Services\Transmission\TransmissionService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class LookupApiTest extends TestCase
{
    public function test_categories_endpoint_returns_data(): void
    {
        $this->mockService(CategoryService::class, [
            'getCategories' => new Collection([
                ['id' => 1, 'name_en' => 'Economy'],
            ]),
        ]);

        $response = $this->getJson('categories');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'name_en' => 'Economy'],
                ],
            ]);
    }

    public function test_brands_endpoint_returns_data(): void
    {
        $this->mockService(BrandService::class, [
            'getBrands' => new Collection([
                ['id' => 1, 'name_en' => 'Toyota'],
            ]),
        ]);

        $response = $this->getJson('brands');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'name_en' => 'Toyota'],
                ],
            ]);
    }

    public function test_cities_endpoint_returns_data(): void
    {
        $this->mockService(CityService::class, [
            'getCities' => new Collection([
                ['id' => 1, 'name_en' => 'Riyadh'],
            ]),
        ]);

        $response = $this->getJson('cities');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'name_en' => 'Riyadh'],
                ],
            ]);
    }

    public function test_model_years_endpoint_returns_data(): void
    {
        $this->mockService(ModelYearService::class, [
            'getModelYears' => new Collection([
                ['id' => 1, 'year' => 2025],
            ]),
        ]);

        $response = $this->getJson('model-years');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'year' => 2025],
                ],
            ]);
    }

    public function test_transmissions_endpoint_returns_data(): void
    {
        $this->mockService(TransmissionService::class, [
            'getTransmissions' => new Collection([
                ['id' => 1, 'name_en' => 'Automatic'],
            ]),
        ]);

        $response = $this->getJson('transmissions');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'name_en' => 'Automatic'],
                ],
            ]);
    }

    public function test_fuel_types_endpoint_returns_data(): void
    {
        $this->mockService(FuelTypeService::class, [
            'getFuelTypes' => new Collection([
                ['id' => 1, 'name_en' => 'Gasoline 91'],
            ]),
        ]);

        $response = $this->getJson('fuel-types');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    ['id' => 1, 'name_en' => 'Gasoline 91'],
                ],
            ]);
    }

    public function test_policies_endpoint_returns_data(): void
    {
        $this->mockService(PolicyService::class, [
            'getPolicies' => null,
        ]);

        $response = $this->getJson('policies');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'errors' => [],
            ]);
    }
}
