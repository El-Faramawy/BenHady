<?php

declare(strict_types=1);

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id'     => ['nullable', 'integer', 'exists:cities,id'],
            'pickup_date' => ['nullable', 'date_format:Y-m-d'],
            'pickup_time' => ['nullable', 'date_format:H:i'],
        ];
    }
}
