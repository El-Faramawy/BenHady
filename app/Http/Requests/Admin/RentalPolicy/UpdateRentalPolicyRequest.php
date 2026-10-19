<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\RentalPolicy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRentalPolicyRequest extends FormRequest
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
            'tenant_instructions_ar' => ['sometimes', 'required', 'string'],
            'tenant_instructions_en' => ['nullable', 'string'],
            'insurance_policy_ar'    => ['sometimes', 'required', 'string'],
            'insurance_policy_en'    => ['nullable', 'string'],
            'cancellation_policy_ar' => ['sometimes', 'required', 'string'],
            'cancellation_policy_en' => ['nullable', 'string'],
        ];
    }
}
