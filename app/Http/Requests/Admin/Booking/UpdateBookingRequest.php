<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'user_id'          => ['required', 'integer', 'exists:users,id'],
            'car_id'           => ['required', 'integer', 'exists:cars,id'],
            'pickup_branch_id' => ['required', 'integer', 'exists:branches,id'],
            'return_branch_id' => ['required', 'integer', 'exists:branches,id'],
            'pickup_at'        => ['required', 'date'],
            'return_at'        => ['required', 'date', 'after:pickup_at'],
            'daily_price'      => ['nullable', 'numeric', 'min:0'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'status'           => ['required', 'string', 'in:pending,active,completed,cancelled'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }
}
