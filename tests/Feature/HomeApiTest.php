<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Home\HomeService;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    public function test_home_endpoint_returns_success_with_aggregated_data(): void
    {
        $mockData = [
            'categories' => [
                ['id' => 1, 'name_ar' => 'اقتصادية', 'name_en' => 'Economy'],
            ],
            'brands' => [
                ['id' => 1, 'name_ar' => 'تويوتا', 'name_en' => 'Toyota'],
            ],
            'featured_cars' => [
                ['id' => 1, 'name_en' => 'Toyota Camry 2025', 'daily_price' => 220],
            ],
            'handpicked_cars' => [
                ['id' => 2, 'name_en' => 'Toyota Yaris 2024', 'daily_price' => 130],
            ],
        ];

        $this->mockService(HomeService::class, [
            'getHomeData' => $mockData,
        ]);

        $response = $this->getJson('home');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => $mockData,
                'errors' => [],
            ]);
    }
}
