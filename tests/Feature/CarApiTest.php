<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Exceptions\Car\CarNotFoundException;
use App\Services\Car\CarService;
use App\Services\Car\DTO\CarFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $mockDetails = [
            'car' => [
                'id' => 1,
                'name_en' => 'Toyota Camry 2025',
                'daily_price' => 220,
            ],
            'similar_cars' => [],
        ];

        $this->mockService(CarService::class, [
            'getCarDetails' => $mockDetails,
        ]);

        $response = $this->getJson('cars/1');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockDetails,
                'errors' => [],
            ]);
    }

    public function test_car_show_returns_404_when_car_not_found(): void
    {
        $mock = $this->mockService(CarService::class);
        $mock->shouldReceive('getCarDetails')
            ->once()
            ->with(999)
            ->andThrow(new CarNotFoundException());

        $response = $this->getJson('cars/999');

        $response->assertStatus(404)
            ->assertJson([
                'code' => 404,
                'data' => null,
            ]);
    }
}
