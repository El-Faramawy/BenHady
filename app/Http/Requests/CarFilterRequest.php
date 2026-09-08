<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'     => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id'        => ['nullable', 'integer', 'exists:brands,id'],
            'city_id'         => ['nullable', 'integer', 'exists:cities,id'],
            'branch_id'       => ['nullable', 'integer', 'exists:branches,id'],
            'model_year_id'   => ['nullable', 'integer', 'exists:model_years,id'],
            'transmission_id' => ['nullable', 'integer', 'exists:transmissions,id'],
            'fuel_type_id'    => ['nullable', 'integer', 'exists:fuel_types,id'],
            'min_price'       => ['nullable', 'numeric', 'min:0'],
            'max_price'       => ['nullable', 'numeric', 'min:0'],
            'seats'           => ['nullable', 'integer', 'min:1'],
            'search'          => ['nullable', 'string', 'max:100'],
            'sort_by'         => ['nullable', 'string', 'in:price,year,created_at'],
            'sort_order'      => ['nullable', 'string', 'in:asc,desc'],
            'per_page'        => ['nullable', 'integer', 'min:1', 'max:50'],
            'page'            => ['nullable', 'integer', 'min:1'],
        ];
    }
}
