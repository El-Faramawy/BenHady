<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ModelYear;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModelYearRequest extends FormRequest
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
        $id = $this->route('model_year')?->id ?? $this->route('model_year');

        return [
            'year'      => ['required', 'integer', 'min:1900', 'max:2100', Rule::unique('model_years', 'year')->ignore($id)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
