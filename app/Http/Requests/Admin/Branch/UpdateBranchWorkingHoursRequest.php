<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Branch;

use App\Enums\Branch\DayOfWeekEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchWorkingHoursRequest extends FormRequest
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
            'working_hours'                        => ['required', 'array'],
            'working_hours.*.day_of_week'          => ['required', Rule::enum(DayOfWeekEnum::class)],
            'working_hours.*.is_open'              => ['nullable', 'boolean'],
            'working_hours.*.is_24_hours'          => ['nullable', 'boolean'],
            'working_hours.*.open_at'              => ['nullable', 'string'],
            'working_hours.*.close_at'             => ['nullable', 'string'],
            'working_hours.*.reservation_start_at' => ['nullable', 'string'],
            'working_hours.*.reservation_close_at' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $rawHours = $this->input('working_hours', []);
        if (is_array($rawHours)) {
            $cleaned = [];
            foreach ($rawHours as $key => $hour) {
                if (is_array($hour)) {
                    $hour['is_open'] = filter_var($hour['is_open'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    $hour['is_24_hours'] = filter_var($hour['is_24_hours'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    $cleaned[$key] = $hour;
                }
            }
            $this->merge(['working_hours' => $cleaned]);
        }
    }
}
