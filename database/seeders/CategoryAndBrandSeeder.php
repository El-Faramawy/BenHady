<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryAndBrandSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_ar' => 'اقتصادية', 'name_en' => 'Economy', 'icon' => 'categories/economy.png'],
            ['name_ar' => 'سيدان', 'name_en' => 'Sedan', 'icon' => 'categories/sedan.png'],
            ['name_ar' => 'عائلية وSUV', 'name_en' => 'Family & SUV', 'icon' => 'categories/suv.png'],
            ['name_ar' => 'فاخرة', 'name_en' => 'Luxury', 'icon' => 'categories/luxury.png'],
            ['name_ar' => 'رياضية', 'name_en' => 'Sports', 'icon' => 'categories/sports.png'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name_en' => $cat['name_en']],
                ['name_ar' => $cat['name_ar'], 'icon' => $cat['icon'], 'is_active' => true]
            );
        }

        $brands = [
            ['name_ar' => 'تويوتا', 'name_en' => 'Toyota', 'logo_url' => 'brands/toyota.png'],
            ['name_ar' => 'هيونداي', 'name_en' => 'Hyundai', 'logo_url' => 'brands/hyundai.png'],
            ['name_ar' => 'كيا', 'name_en' => 'Kia', 'logo_url' => 'brands/kia.png'],
            ['name_ar' => 'نيسان', 'name_en' => 'Nissan', 'logo_url' => 'brands/nissan.png'],
            ['name_ar' => 'مرسيدس بنز', 'name_en' => 'Mercedes-Benz', 'logo_url' => 'brands/mercedes.png'],
            ['name_ar' => 'بي إم دبليو', 'name_en' => 'BMW', 'logo_url' => 'brands/bmw.png'],
            ['name_ar' => 'شفروليه', 'name_en' => 'Chevrolet', 'logo_url' => 'brands/chevrolet.png'],
        ];

        foreach ($brands as $b) {
            Brand::firstOrCreate(
                ['name_en' => $b['name_en']],
                ['name_ar' => $b['name_ar'], 'logo_url' => $b['logo_url'], 'is_active' => true]
            );
        }
    }
}
