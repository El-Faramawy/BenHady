<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Exceptions\Car\CarNotFoundException;
use App\Services\Car\CarService;
use App\Services\Car\DTO\CarDetailDTO;
use App\Services\Car\DTO\CarFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class CarApiTest extends TestCase
{
    public function test_cars_index_returns_paginated_cars(): void
    {
        $paginator = new LengthAwarePaginator(
            items: [
                ['id' => 1, 'name_en' => 'Toyota Camry 2025', 'daily_price' => 220],
            ],
            total: 1,
            perPage: 15,
            currentPage: 1
        );

        $this->mockService(CarService::class, [
            'getCars' => $paginator,
        ]);

        $response = $this->getJson('cars?page=1');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'current_page' => 1,
                    'total' => 1,
                ],
                'errors' => [],
            ]);
    }

    public function test_car_show_returns_car_details(): void
    {
        $mockCar = [
            'id' => 1,
            'name_en' => 'Toyota Camry 2025',
            'name_ar' => 'تويوتا كامري 2025',
            'daily_price' => '220.00',
            'branches' => [
                [
                    'id' => 1,
                    'city_id' => 1,
                    'name_en' => 'King Khalid Airport Branch',
                ],
                [
                    'id' => 2,
                    'city_id' => 1,
                    'name_en' => 'King Fahd Road Branch',
                ],
            ],
        ];

        $this->mockService(CarService::class, [
            'getCarDetails' => $mockCar,
        ]);

        $response = $this->getJson('cars/1');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockCar,
                'errors' => [],
            ]);
    }

    public function test_car_show_accepts_city_id_filter(): void
    {
        $this->mockPresenceVerifier(1);

        $mockCar = [
            'id' => 1,
            'name_en' => 'Toyota Camry 2025',
            'branches' => [
                [
                    'id' => 1,
                    'city_id' => 1,
                ],
            ],
        ];

        $mock = $this->mockService(CarService::class);
        $mock->shouldReceive('getCarDetails')
            ->once()
            ->with(Mockery::on(fn($dto) => $dto instanceof CarDetailDTO && $dto->id === 1 && $dto->cityId === 1))
            ->andReturn($mockCar);

        $response = $this->getJson('cars/1?city_id=1');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockCar,
                'errors' => [],
            ]);
    }

    public function test_car_show_returns_404_when_car_not_found(): void
    {
        $mock = $this->mockService(CarService::class);
        $mock->shouldReceive('getCarDetails')
            ->once()
            ->with(Mockery::on(fn($dto) => $dto instanceof CarDetailDTO && $dto->id === 999))
            ->andThrow(new CarNotFoundException());

        $response = $this->getJson('cars/999');

        $response->assertStatus(404)
            ->assertJson([
                'code' => 404,
                'data' => null,
            ]);
    }
}
