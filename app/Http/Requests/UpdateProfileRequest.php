<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\User\UserTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['sometimes', 'string', Rule::unique('users', 'phone')->ignore($userId)],
            'date_of_birth' => 'sometimes|date',
            'type' => ['sometimes', 'string', Rule::in(array_column(UserTypeEnum::cases(), 'value'))],
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'driving_license_number' => 'sometimes|nullable|string',
            'license_expiry_date' => 'sometimes|nullable|date',
            'id_number' => ['sometimes', 'nullable', 'string', Rule::unique('users', 'id_number')->ignore($userId)],
            'id_number_end_date' => 'sometimes|nullable|date',
            'version_number' => 'sometimes|nullable|string',
            'license_number' => 'sometimes|nullable|string',
            'border_entry_number' => ['sometimes', 'nullable', 'string', Rule::unique('users', 'border_entry_number')->ignore($userId)],
            'language' => 'sometimes|nullable|string|in:ar,en',
        ];
    }
}
