<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ModelYear;

use Illuminate\Foundation\Http\FormRequest;

class StoreModelYearRequest extends FormRequest
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
            'year'      => ['required', 'integer', 'min:1900', 'max:2100', 'unique:model_years,year'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
