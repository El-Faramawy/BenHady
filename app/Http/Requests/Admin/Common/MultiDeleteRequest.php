<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Common;

use Illuminate\Foundation\Http\FormRequest;

class MultiDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->ids)) {
            $parsed = array_filter(
                array_map('trim', explode(',', $this->ids)),
                fn ($val): bool => is_numeric($val)
            );

            $this->merge([
                'ids' => array_values(array_map('intval', $parsed)),
            ]);
        }
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'min:1'],
        ];
    }
}
