<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            ['id' => 1],
            [
                'logo' => 'images/logo.png',
                'fav_icon' => 'images/favicon.ico',
                'about_us_ar' => 'بن هادي لتأجير السيارات، وجهتك الأولى لتجربة قيادة مريحة وآمنة بأحدث موديلات السيارات في المملكة.',
                'about_us_en' => 'BenHady Car Rental, your premier destination for a comfortable and safe driving experience with the latest car models in Saudi Arabia.',
                'terms_conditions_ar' => 'الشروط والأحكام الخاصة باستخدام تطبيق وخدمات بن هادي لتأجير السيارات.',
                'terms_conditions_en' => 'Terms and conditions for using BenHady car rental application and services.',
                'privacy_policy_ar' => 'نحن في بن هادي نلتزم بحماية خصوصية بياناتك ومعلوماتك الشخصية وفقاً لأعلى معايير الأمان.',
                'privacy_policy_en' => 'At BenHady, we are committed to protecting your privacy and personal data in accordance with the highest security standards.',
            ]
        );
    }
}
