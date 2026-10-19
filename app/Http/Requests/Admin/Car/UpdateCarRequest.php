<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Car;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
            'name_ar'          => ['required', 'string', 'max:255'],
            'name_en'          => ['required', 'string', 'max:255'],
            'brand_id'         => ['required', 'integer', 'exists:brands,id'],
            'category_id'      => ['required', 'integer', 'exists:categories,id'],
            'branch_id'        => ['required', 'integer', 'exists:branches,id'],
            'model_year_id'    => ['required', 'integer', 'exists:model_years,id'],
            'transmission_id'  => ['required', 'integer', 'exists:transmissions,id'],
            'fuel_type_id'     => ['required', 'integer', 'exists:fuel_types,id'],
            'seats'            => ['required', 'integer', 'min:1', 'max:50'],
            'daily_price'      => ['required', 'numeric', 'min:0'],
            'description_ar'   => ['nullable', 'string'],
            'description_en'   => ['nullable', 'string'],
            'is_featured'      => ['nullable', 'boolean'],
            'is_handpicked'    => ['nullable', 'boolean'],
            'is_active'        => ['nullable', 'boolean'],
            'status'           => ['required', 'string', 'in:available,rented,maintenance,reserved'],
            'features'         => ['nullable', 'array'],
            'features.*'       => ['integer', 'exists:features,id'],
            'branches'         => ['nullable', 'array'],
            'branches.*'       => ['integer', 'exists:branches,id'],
            'primary_image'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'gallery_images'   => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'delete_images'    => ['nullable', 'array'],
            'delete_images.*'  => ['integer', 'exists:car_images,id'],
        ];
    }
}
