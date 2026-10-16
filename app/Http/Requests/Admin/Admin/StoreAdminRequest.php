<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email|max:255',
            'phone'    => 'nullable|string|max:20|unique:admins,phone',
            'password' => 'required|string|min:6',
        ];
    }
}
