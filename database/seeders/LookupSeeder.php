<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\ModelYear;
use App\Models\Transmission;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    public function run(): void
    {
        // Model Years
        $years = [2020, 2021, 2022, 2023, 2024, 2025, 2026];
        foreach ($years as $year) {
            ModelYear::firstOrCreate(
                ['year' => $year],
                ['is_active' => true]
            );
        }

        // Transmissions
        $transmissions = [
            ['name_ar' => 'أوتوماتيك', 'name_en' => 'Automatic'],
            ['name_ar' => 'يدوي', 'name_en' => 'Manual'],
            ['name_ar' => 'سي في تي', 'name_en' => 'CVT'],
        ];
        foreach ($transmissions as $item) {
            Transmission::firstOrCreate(
                ['name_en' => $item['name_en']],
                ['name_ar' => $item['name_ar'], 'is_active' => true]
            );
        }

        // Fuel Types
        $fuelTypes = [
            ['name_ar' => 'بنزين 91', 'name_en' => 'Gasoline 91'],
            ['name_ar' => 'بنزين 95', 'name_en' => 'Gasoline 95'],
            ['name_ar' => 'ديزل', 'name_en' => 'Diesel'],
            ['name_ar' => 'هجين (هايبرد)', 'name_en' => 'Hybrid'],
            ['name_ar' => 'كهرباء', 'name_en' => 'Electric'],
        ];
        foreach ($fuelTypes as $item) {
            FuelType::firstOrCreate(
                ['name_en' => $item['name_en']],
                ['name_ar' => $item['name_ar'], 'is_active' => true]
            );
        }
    }
}
