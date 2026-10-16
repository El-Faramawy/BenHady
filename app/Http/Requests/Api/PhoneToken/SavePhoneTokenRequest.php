<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\PhoneToken;

use App\Http\Requests\BaseFormRequest;

class SavePhoneTokenRequest extends BaseFormRequest
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
