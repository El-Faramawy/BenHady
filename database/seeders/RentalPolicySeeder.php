<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RentalPolicy;
use Illuminate\Database\Seeder;

class RentalPolicySeeder extends Seeder
{
    public function run(): void
    {
        RentalPolicy::firstOrCreate(
            ['id' => 1],
            [
                'insurance_policy_ar' => 'يشمل التأمين الشامل الحوادث والأضرار غير المتعمدة وفقاً للشروط والأحكام. يتحمل المستأجر نسبة التحمل المقررة في حال الخطأ بنسبة 100%.',
                'insurance_policy_en' => 'Comprehensive insurance covers accidents and unintentional damage according to terms and conditions. The lessee bears the deductible in case of 100% liability.',
                'cancellation_policy_ar' => 'يمكنك إلغاء الحجز مجاناً حتى 24 ساعة قبل موعد استلام السيارة. في حالة الإلغاء قبل أقل من 24 ساعة، يتم خصم قيمة يوم تأجير واحد.',
                'cancellation_policy_en' => 'Free cancellation up to 24 hours prior to pick-up time. For cancellations under 24 hours, a one-day rental fee applies.',
                'tenant_instructions_ar' => '1. إحضار الهوية الوطنية أو الإقامة سارية المفعول.' . PHP_EOL . '2. رخصة قيادة سارية المفعول.' . PHP_EOL . '3. بطاقة ائتمانية أو بطاقة مدى للدفع.' . PHP_EOL . '4. تسليم السيارة بنفس كمية الوقود المستلمة.',
                'tenant_instructions_en' => '1. Valid National ID or Iqama.' . PHP_EOL . '2. Valid Driving License.' . PHP_EOL . '3. Credit or Mada card for payment.' . PHP_EOL . '4. Return the car with the same fuel level.',
            ]
        );
    }
}
