<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question_ar' => 'ما هي متطلبات استئجار سيارة من بن هادي؟',
                'question_en' => 'What are the requirements to rent a car from BenHady?',
                'answer_ar' => 'يتطلب استئجار سيارة هوية وطنية أو إقامة سارية المفعول للمواطنين والمقيمين، أو جواز سفر ساري للزوار، ورخصة قيادة سارية المفعول، بالإضافة إلى بطاقة دفع إلكترونية أو رصيد محفظة.',
                'answer_en' => 'Renting a car requires a valid National ID or Iqama for citizens and residents, or a valid passport for visitors, a valid driving license, and an electronic payment card or wallet balance.',
                'sort_order' => 1,
            ],
            [
                'question_ar' => 'هل يمكن استلام السيارة من فرع وتسليمها في فرع آخر؟',
                'question_en' => 'Can I pick up the car from one branch and drop it off at another?',
                'answer_ar' => 'نعم، نوفر خدمة تسليم السيارة في فرع مختلف داخل المملكة مع إمكانية تطبيق رسوم إضافية حسب المسافة بين الفروع.',
                'answer_en' => 'Yes, we offer cross-branch drop-off service across the Kingdom with applicable additional fees depending on the distance between branches.',
                'sort_order' => 2,
            ],
            [
                'question_ar' => 'ما هي سياسة إلغاء الحجز واسترداد المبلغ؟',
                'question_en' => 'What is the cancellation and refund policy?',
                'answer_ar' => 'يمكنك إلغاء الحجز مجاناً قبل موعد الاستلام المحدد بـ 24 ساعة، وسيتم استرداد كامل المبلغ إلى محفظتك أو وسيلة الدفع الأصلية.',
                'answer_en' => 'You can cancel your reservation for free up to 24 hours prior to the scheduled pickup time, and the full amount will be refunded to your wallet or original payment method.',
                'sort_order' => 3,
            ],
            [
                'question_ar' => 'هل يتضمن سعر الإيجار التأمين الشامل؟',
                'question_en' => 'Does the rental price include comprehensive insurance?',
                'answer_ar' => 'تشمل جميع أسعارنا التأمين الإلزامي ضد الغير، كما يمكنك اختيار باقة التأمين الشامل أثناء الحجز لتغطية كاملة مع نسبة تحمل منخفضة.',
                'answer_en' => 'All our rates include mandatory third-party insurance, and you can select the comprehensive insurance add-on during booking for full coverage with lower excess.',
                'sort_order' => 4,
            ],
            [
                'question_ar' => 'ما هو الحد الأدنى للعمر المسموح به لاستئجار سيارة؟',
                'question_en' => 'What is the minimum age required to rent a car?',
                'answer_ar' => 'الحد الأدنى للعمر هو 21 عاماً لجميع الفئات القياسية، و25 عاماً للسيارات الفاخرة والرياضية.',
                'answer_en' => 'The minimum age is 21 years for standard categories, and 25 years for luxury and sports vehicles.',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question_ar' => $faq['question_ar']],
                $faq
            );
        }
    }
}
