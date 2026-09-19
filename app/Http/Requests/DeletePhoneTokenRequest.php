<?php

declare(strict_types=1);

namespace App\Http\Requests;

class DeletePhoneTokenRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'phone_token' => ['required', 'string'],
        ];
    }
}
