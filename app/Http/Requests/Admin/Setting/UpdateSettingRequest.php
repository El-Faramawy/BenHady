<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'name'                => ['sometimes', 'required', 'string', 'max:255'],
            'logo'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'fav_icon'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,ico', 'max:1024'],
            'about_us_ar'         => ['nullable', 'string'],
            'about_us_en'         => ['nullable', 'string'],
            'terms_conditions_ar' => ['nullable', 'string'],
            'terms_conditions_en' => ['nullable', 'string'],
            'privacy_policy_ar'   => ['nullable', 'string'],
            'privacy_policy_en'   => ['nullable', 'string'],
        ];
    }
}
