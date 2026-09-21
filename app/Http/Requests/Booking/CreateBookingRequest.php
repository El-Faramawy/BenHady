<?php

declare(strict_types=1);

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'pickup_branch_id' => ['required', 'integer', 'exists:branches,id'],
            'return_branch_id' => ['required', 'integer', 'exists:branches,id'],
            'pickup_at' => ['required', 'date', 'after:now'],
            'return_at' => ['required', 'date', 'after:pickup_at'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
