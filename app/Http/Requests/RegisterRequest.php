<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\User\UserTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseFormRequest
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
        $type = $this->input('type');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'date_of_birth' => 'required|date',
            'type' => ['required', 'string', Rule::in(array_column(UserTypeEnum::cases(), 'value'))],
            'password' => 'required|string|min:8|confirmed',
            'driving_license_number' => 'required|string',
            'license_expiry_date' => 'required|date',
        ];

        if ($type === UserTypeEnum::NATIONAL_ID->value || $type === UserTypeEnum::RESIDENT_ID->value) {
            $rules['id_number'] = 'required|string|unique:users,id_number';
            $rules['id_number_end_date'] = 'required|date';
            $rules['version_number'] = 'required|string';
        }

        if ($type === UserTypeEnum::VISITOR->value) {
            $rules['license_number'] = 'required|string';
            $rules['border_entry_number'] = 'required|string|unique:users,border_entry_number';
        }

        return $rules;
    }
}
