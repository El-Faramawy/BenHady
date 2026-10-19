<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        /** @var User|null $user */
        $user = $this->route('user');
        $userId = $user instanceof User ? $user->id : (int) $user;

        return [
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone'                  => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password'               => ['nullable', 'string', 'min:6'],
            'type'                   => ['required', 'string', 'in:national_id,resident_id,visitor'],
            'date_of_birth'          => ['nullable', 'date'],
            'id_number'              => [
                Rule::requiredIf(fn () => in_array($this->input('type'), ['national_id', 'resident_id'])),
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'id_number')->ignore($userId),
            ],
            'id_number_end_date'     => [
                Rule::requiredIf(fn () => in_array($this->input('type'), ['national_id', 'resident_id'])),
                'nullable',
                'date',
            ],
            'version_number'         => [
                Rule::requiredIf(fn () => in_array($this->input('type'), ['national_id', 'resident_id'])),
                'nullable',
                'string',
                'max:50',
            ],
            'license_number'         => [
                Rule::requiredIf(fn () => $this->input('type') === 'visitor'),
                'nullable',
                'string',
                'max:50',
            ],
            'border_entry_number'    => [
                Rule::requiredIf(fn () => $this->input('type') === 'visitor'),
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'border_entry_number')->ignore($userId),
            ],
            'driving_license_number' => ['nullable', 'string', 'max:50'],
            'license_expiry_date'    => ['nullable', 'date'],
            'wallet'                 => ['nullable', 'numeric', 'min:0'],
            'status'                 => ['required', 'string', 'in:active,in_active,pending,rejected'],
            'phone_verified'         => ['nullable', 'boolean'],
            'id_verified'            => ['nullable', 'boolean'],
            'licence_verified'       => ['nullable', 'boolean'],
        ];
    }
}
