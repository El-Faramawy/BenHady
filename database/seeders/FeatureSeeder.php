<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['name_ar' => 'بلوتوث', 'name_en' => 'Bluetooth', 'icon' => 'features/bluetooth.svg'],
            ['name_ar' => 'كاميرا خلفية', 'name_en' => 'Rear Camera', 'icon' => 'features/camera.svg'],
            ['name_ar' => 'حساسات أمامية وخلفية', 'name_en' => 'Parking Sensors', 'icon' => 'features/sensors.svg'],
            ['name_ar' => 'مثبت سرعة ذكي', 'name_en' => 'Smart Cruise Control', 'icon' => 'features/cruise.svg'],
            ['name_ar' => 'أبل كاربلاي / أندرويد أوتو', 'name_en' => 'Apple CarPlay / Android Auto', 'icon' => 'features/carplay.svg'],
            ['name_ar' => 'نظام الملاحة GPS', 'name_en' => 'GPS Navigation', 'icon' => 'features/gps.svg'],
            ['name_ar' => 'فتحة سقف بانوراما', 'name_en' => 'Panoramic Sunroof', 'icon' => 'features/sunroof.svg'],
            ['name_ar' => 'مقاعد جلدية فاخرة', 'name_en' => 'Leather Seats', 'icon' => 'features/seats.svg'],
            ['name_ar' => 'دخول وتشغيل ذكي (بصمة)', 'name_en' => 'Smart Keyless Entry & Start', 'icon' => 'features/keyless.svg'],
            ['name_ar' => 'تكييف هواء أوتوماتيكي ثنائي', 'name_en' => 'Dual Automatic Climate Control', 'icon' => 'features/ac.svg'],
        ];

        foreach ($features as $f) {
            Feature::firstOrCreate(
                ['name_en' => $f['name_en']],
                ['name_ar' => $f['name_ar'], 'icon' => $f['icon']]
            );
        }
    }
}
