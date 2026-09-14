<?php

declare(strict_types=1);

namespace Tests\Feature\Setting;

use App\Models\Setting;
use App\Services\Setting\SettingService;
use Tests\TestCase;

class SettingTest extends TestCase
{
    public function test_settings_endpoint_returns_data(): void
    {
        $setting = new Setting([
            'logo' => 'images/logo.png',
            'fav_icon' => 'images/favicon.ico',
            'about_us_ar' => 'عن بن هادي',
            'about_us_en' => 'About BenHady',
            'terms_conditions_ar' => 'الشروط والأحكام',
            'terms_conditions_en' => 'Terms and Conditions',
            'privacy_policy_ar' => 'سياسة الخصوصية',
            'privacy_policy_en' => 'Privacy Policy',
        ]);

        $this->mockService(SettingService::class, [
            'getSettings' => $setting,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('settings');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'logo' => 'images/logo.png',
                    'fav_icon' => 'images/favicon.ico',
                    'about_us' => 'عن بن هادي',
                    'terms_conditions' => 'الشروط والأحكام',
                    'privacy_policy' => 'سياسة الخصوصية',
                ],
                'errors' => [],
            ]);
    }
}
