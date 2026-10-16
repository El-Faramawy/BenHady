<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Faq;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_ar' => ['required', 'string', 'max:255'],
            'question_en' => ['required', 'string', 'max:255'],
            'answer_ar'   => ['required', 'string'],
            'answer_en'   => ['required', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }
}
