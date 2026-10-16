<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Feature;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
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
            'name_ar'   => ['required', 'string', 'max:255'],
            'name_en'   => ['required', 'string', 'max:255'],
            'icon'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
