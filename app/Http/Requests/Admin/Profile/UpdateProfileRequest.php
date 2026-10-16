<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $adminId = admin()->id();

        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($adminId),
            ],
            'password'         => ['nullable', 'string', 'min:6', 'same:confirm_password'],
            'confirm_password' => ['nullable', 'required_with:password'],
        ];
    }
}
