<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\RentalPolicy;
use App\Services\Category\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class LocalizedAttributesTest extends TestCase
{
    public function test_category_model_returns_localized_name_in_arabic_by_default(): void
    {
        $category = new Category([
            'name_ar' => 'اقتصادية',
            'name_en' => 'Economy',
        ]);

        $this->assertSame('اقتصادية', $category->name);
        $this->assertArrayHasKey('name', $category->toArray());
        $this->assertSame('اقتصادية', $category->toArray()['name']);
    }

    public function test_category_model_returns_localized_name_in_english_when_locale_is_en(): void
    {
        app()->setLocale('en');

        $category = new Category([
            'name_ar' => 'اقتصادية',
            'name_en' => 'Economy',
        ]);

        $this->assertSame('Economy', $category->name);
        $this->assertSame('Economy', $category->toArray()['name']);
    }

    public function test_brand_model_returns_localized_name(): void
    {
        app()->setLocale('en');
        $brand = new Brand(['name_ar' => 'تويوتا', 'name_en' => 'Toyota']);
        $this->assertSame('Toyota', $brand->name);

        app()->setLocale('ar');
        $this->assertSame('تويوتا', $brand->name);
    }

    public function test_car_model_returns_localized_name_and_description(): void
    {
        $car = new Car([
            'name_ar' => 'تويوتا كامري',
            'name_en' => 'Toyota Camry',
            'description_ar' => 'وصف بالعربي',
            'description_en' => 'English description',
        ]);

        app()->setLocale('ar');
        $this->assertSame('تويوتا كامري', $car->name);
        $this->assertSame('وصف بالعربي', $car->description);

        app()->setLocale('en');
        $this->assertSame('Toyota Camry', $car->name);
        $this->assertSame('English description', $car->description);
    }

    public function test_rental_policy_returns_localized_attributes(): void
    {
        $policy = new RentalPolicy([
            'insurance_policy_ar' => 'تأمين شامل',
            'insurance_policy_en' => 'Comprehensive Insurance',
            'cancellation_policy_ar' => 'إلغاء مجاني',
            'cancellation_policy_en' => 'Free Cancellation',
            'tenant_instructions_ar' => 'تعليمات المستأجر',
            'tenant_instructions_en' => 'Tenant Instructions',
        ]);

        app()->setLocale('ar');
        $this->assertSame('تأمين شامل', $policy->insurance_policy);
        $this->assertSame('إلغاء مجاني', $policy->cancellation_policy);
        $this->assertSame('تعليمات المستأجر', $policy->tenant_instructions);

        app()->setLocale('en');
        $this->assertSame('Comprehensive Insurance', $policy->insurance_policy);
        $this->assertSame('Free Cancellation', $policy->cancellation_policy);
        $this->assertSame('Tenant Instructions', $policy->tenant_instructions);
    }

    public function test_api_accept_language_header_controls_serialization(): void
    {
        $mockCategory = new Category([
            'id' => 1,
            'name_ar' => 'سيدان',
            'name_en' => 'Sedan',
            'is_active' => true,
        ]);

        $this->mockService(CategoryService::class, [
            'getCategories' => new Collection([$mockCategory]),
        ]);

        // Request with Accept-Language: en
        $responseEn = $this->withHeaders(['Accept-Language' => 'en'])
            ->getJson('categories');

        $responseEn->assertStatus(200);
        $dataEn = $responseEn->json('data.0');
        $this->assertSame('Sedan', $dataEn['name']);

        // Request with Accept-Language: ar
        $responseAr = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('categories');

        $responseAr->assertStatus(200);
        $dataAr = $responseAr->json('data.0');
        $this->assertSame('سيدان', $dataAr['name']);

        // Request without header defaults to Arabic
        $responseDefault = $this->getJson('categories');
        $dataDefault = $responseDefault->json('data.0');
        $this->assertSame('سيدان', $dataDefault['name']);
    }
}
