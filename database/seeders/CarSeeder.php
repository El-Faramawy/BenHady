<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Car\CarStatusEnum;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FuelType;
use App\Models\ModelYear;
use App\Models\Transmission;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $toyota = Brand::where('name_en', 'Toyota')->first();
        $hyundai = Brand::where('name_en', 'Hyundai')->first();
        $kia = Brand::where('name_en', 'Kia')->first();
        $mercedes = Brand::where('name_en', 'Mercedes-Benz')->first();

        $economy = Category::where('name_en', 'Economy')->first();
        $sedan = Category::where('name_en', 'Sedan')->first();
        $suv = Category::where('name_en', 'Family & SUV')->first();
        $luxury = Category::where('name_en', 'Luxury')->first();

        $auto = Transmission::where('name_en', 'Automatic')->first();
        $cvt = Transmission::where('name_en', 'CVT')->first();

        $gas91 = FuelType::where('name_en', 'Gasoline 91')->first();
        $gas95 = FuelType::where('name_en', 'Gasoline 95')->first();
        $hybrid = FuelType::where('name_en', 'Hybrid')->first();

        $year2024 = ModelYear::where('year', 2024)->first();
        $year2025 = ModelYear::where('year', 2025)->first();

        $branches = Branch::all();
        $branch1 = $branches->first();
        $branch2 = $branches->skip(1)->first() ?? $branch1;

        $features = Feature::all();

        $carsData = [
            [
                'brand_id' => $toyota?->id,
                'category_id' => $sedan?->id,
                'branch_id' => $branch1?->id,
                'model_year_id' => $year2025?->id,
                'transmission_id' => $auto?->id,
                'fuel_type_id' => $gas91?->id,
                'name_ar' => 'تويوتا كامري 2025',
                'name_en' => 'Toyota Camry 2025',
                'seats' => 5,
                'daily_price' => 220.00,
                'description_ar' => 'سيارة سيدان مريحة واقتصادية، مثالية للتنقل اليومي والسفر بين المدن بأعلى معايير الأمان والراحة.',
                'description_en' => 'Comfortable and economical sedan, perfect for daily commuting and long drives with highest safety standards.',
                'is_featured' => true,
                'is_handpicked' => true,
                'is_active' => true,
                'status' => CarStatusEnum::AVAILABLE->value,
                'images' => [
                    ['image_path' => 'cars/camry_front.jpg', 'is_primary' => true, 'sort_order' => 1],
                    ['image_path' => 'cars/camry_side.jpg', 'is_primary' => false, 'sort_order' => 2],
                    ['image_path' => 'cars/camry_interior.jpg', 'is_primary' => false, 'sort_order' => 3],
                ],
            ],
            [
                'brand_id' => $hyundai?->id,
                'category_id' => $sedan?->id,
                'branch_id' => $branch1?->id,
                'model_year_id' => $year2024?->id,
                'transmission_id' => $auto?->id,
                'fuel_type_id' => $gas91?->id,
                'name_ar' => 'هيونداي إلنترا 2024',
                'name_en' => 'Hyundai Elantra 2024',
                'seats' => 5,
                'daily_price' => 170.00,
                'description_ar' => 'سيارة عملية بتصميم عصري جريء واستهلاك وقود ممتاز وتقنيات قيادة متقدمة.',
                'description_en' => 'Practical car with bold modern styling, outstanding fuel economy, and advanced drive assist features.',
                'is_featured' => true,
                'is_handpicked' => false,
                'is_active' => true,
                'status' => CarStatusEnum::AVAILABLE->value,
                'images' => [
                    ['image_path' => 'cars/elantra_front.jpg', 'is_primary' => true, 'sort_order' => 1],
                    ['image_path' => 'cars/elantra_interior.jpg', 'is_primary' => false, 'sort_order' => 2],
                ],
            ],
            [
                'brand_id' => $toyota?->id,
                'category_id' => $economy?->id,
                'branch_id' => $branch2?->id,
                'model_year_id' => $year2024?->id,
                'transmission_id' => $cvt?->id,
                'fuel_type_id' => $gas91?->id,
                'name_ar' => 'تويوتا يارس 2024',
                'name_en' => 'Toyota Yaris 2024',
                'seats' => 5,
                'daily_price' => 130.00,
                'description_ar' => 'الخيار الاقتصادي الأفضل للمدينة مع سهولة الاصطفاف واستهلاك وقود قليل جداً.',
                'description_en' => 'The ultimate city economy choice with effortless parking and ultra-low fuel consumption.',
                'is_featured' => false,
                'is_handpicked' => true,
                'is_active' => true,
                'status' => CarStatusEnum::AVAILABLE->value,
                'images' => [
                    ['image_path' => 'cars/yaris_front.jpg', 'is_primary' => true, 'sort_order' => 1],
                ],
            ],
            [
                'brand_id' => $mercedes?->id,
                'category_id' => $luxury?->id,
                'branch_id' => $branch1?->id,
                'model_year_id' => $year2025?->id,
                'transmission_id' => $auto?->id,
                'fuel_type_id' => $gas95?->id,
                'name_ar' => 'مرسيدس E-Class 2025',
                'name_en' => 'Mercedes E-Class 2025',
                'seats' => 5,
                'daily_price' => 650.00,
                'description_ar' => 'تجربة الفخامة المطلقة مع مقصورة رقمية متطورة وراحة استثنائية على الطرق.',
                'description_en' => 'The ultimate luxury experience featuring a cutting-edge digital cockpit and superior ride comfort.',
                'is_featured' => true,
                'is_handpicked' => true,
                'is_active' => true,
                'status' => CarStatusEnum::AVAILABLE->value,
                'images' => [
                    ['image_path' => 'cars/mercedes_e_front.jpg', 'is_primary' => true, 'sort_order' => 1],
                    ['image_path' => 'cars/mercedes_e_interior.jpg', 'is_primary' => false, 'sort_order' => 2],
                ],
            ],
            [
                'brand_id' => $kia?->id,
                'category_id' => $suv?->id,
                'branch_id' => $branch2?->id,
                'model_year_id' => $year2024?->id,
                'transmission_id' => $auto?->id,
                'fuel_type_id' => $hybrid?->id,
                'name_ar' => 'كيا سبورتاج 2024',
                'name_en' => 'Kia Sportage 2024',
                'seats' => 5,
                'daily_price' => 250.00,
                'description_ar' => 'سيارة عائلية رياضية متعددة الاستخدامات بمساحات رحبة وتصميم ديناميكي.',
                'description_en' => 'Sporty compact SUV with generous interior room, versatile cargo space, and dynamic styling.',
                'is_featured' => false,
                'is_handpicked' => true,
                'is_active' => true,
                'status' => CarStatusEnum::AVAILABLE->value,
                'images' => [
                    ['image_path' => 'cars/sportage_front.jpg', 'is_primary' => true, 'sort_order' => 1],
                ],
            ],
        ];

        foreach ($carsData as $data) {
            $images = $data['images'];
            unset($data['images']);

            $car = Car::firstOrCreate(
                ['name_en' => $data['name_en']],
                $data
            );

            // Assign images
            if ($car->images()->count() === 0) {
                foreach ($images as $img) {
                    CarImage::create([
                        'car_id' => $car->id,
                        'image_path' => $img['image_path'],
                        'is_primary' => $img['is_primary'],
                        'sort_order' => $img['sort_order'],
                    ]);
                }
            }

            // Sync features
            if ($features->isNotEmpty() && $car->features()->count() === 0) {
                $randomFeatures = $features->random(min(5, $features->count()))->pluck('id')->toArray();
                $car->features()->sync($randomFeatures);
            }
        }
    }
}
