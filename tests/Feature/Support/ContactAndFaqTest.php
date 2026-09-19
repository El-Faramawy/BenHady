<?php

declare(strict_types=1);

namespace Tests\Feature\Support;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\User;
use App\Services\Contact\ContactService;
use App\Services\Faq\FaqService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAndFaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_us_endpoint_returns_201_with_mocked_service(): void
    {
        $mockMessage = new ContactMessage([
            'id' => 1,
            'name' => 'أحمد محمد',
            'email' => 'ahmed@example.com',
            'subject' => 'استفسار عن حجز',
            'booking_number' => '12345',
            'message' => 'أود الاستفسار عن تفاصيل الحجز',
            'reply' => null,
        ]);
        $mockMessage->id = 1;

        $this->mockService(ContactService::class, [
            'sendMessage' => $mockMessage,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('contact-us', [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'subject' => 'استفسار عن حجز',
                'booking_number' => '12345',
                'message' => 'أود الاستفسار عن تفاصيل الحجز',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'code' => 201,
                'data' => [
                    'id' => 1,
                    'name' => 'أحمد محمد',
                    'email' => 'ahmed@example.com',
                    'subject' => 'استفسار عن حجز',
                    'booking_number' => '12345',
                    'message' => 'أود الاستفسار عن تفاصيل الحجز',
                ],
                'messages' => [
                    __('messages.contact.sent_success'),
                ],
                'errors' => [],
            ]);
    }

    public function test_contact_us_fails_validation_without_required_fields(): void
    {
        $response = $this->postJson('contact-us', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'code',
                'message',
            ]);
    }

    public function test_contact_us_validation_errors_are_localized_in_arabic(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('contact-us', []);

        $response->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'message' => 'حقل الاسم مطلوب.',
            ]);
    }

    public function test_contact_us_saves_to_database_with_user_and_reply_nullable(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('contact-us', [
                'name' => 'خالد العتيبي',
                'email' => 'khaled@example.com',
                'subject' => 'طلب مساعدة',
                'booking_number' => 'BK-999',
                'message' => 'أحتاج مساعدة في تعديل موعد الحجز',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('contact_messages', [
            'user_id' => $user->id,
            'name' => 'خالد العتيبي',
            'email' => 'khaled@example.com',
            'subject' => 'طلب مساعدة',
            'booking_number' => 'BK-999',
            'message' => 'أحتاج مساعدة في تعديل موعد الحجز',
            'reply' => null,
        ]);
    }

    public function test_faqs_endpoint_returns_data_with_mocked_service(): void
    {
        $faq = new Faq([
            'id' => 1,
            'question_ar' => 'كيف استأجر سيارة؟',
            'question_en' => 'How to rent a car?',
            'answer_ar' => 'عبر التطبيق بسهولة',
            'answer_en' => 'Easily via the app',
            'sort_order' => 1,
        ]);
        $faq->id = 1;

        $this->mockService(FaqService::class, [
            'getFaqs' => new Collection([$faq]),
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('faqs');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 200,
                'data' => [
                    [
                        'id' => 1,
                        'question' => 'كيف استأجر سيارة؟',
                        'answer' => 'عبر التطبيق بسهولة',
                        'question_ar' => 'كيف استأجر سيارة؟',
                        'question_en' => 'How to rent a car?',
                        'answer_ar' => 'عبر التطبيق بسهولة',
                        'answer_en' => 'Easily via the app',
                        'sort_order' => 1,
                    ],
                ],
                'errors' => [],
            ]);
    }

    public function test_faqs_endpoint_returns_all_faqs_ordered_from_database(): void
    {
        Faq::create([
            'question_ar' => 'السؤال الثاني',
            'question_en' => 'Second Question',
            'answer_ar' => 'الجواب الثاني',
            'answer_en' => 'Second Answer',
            'sort_order' => 2,
        ]);

        Faq::create([
            'question_ar' => 'السؤال الأول',
            'question_en' => 'First Question',
            'answer_ar' => 'الجواب الأول',
            'answer_en' => 'First Answer',
            'sort_order' => 1,
        ]);

        $response = $this->withHeaders(['Accept-Language' => 'en'])
            ->getJson('faqs');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertCount(2, $data);
        $this->assertSame('First Question', $data[0]['question']);
        $this->assertSame('Second Question', $data[1]['question']);
    }
}
