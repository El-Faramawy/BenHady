<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Keys that should be treated as "not found" if 'exists' rule fails.
     */
    protected array $notFoundKeys = [];

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $message = $errors->first();

        $this->checkNotFoundValidation($validator);
        throw new HttpResponseException(
            response()->json([
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => $message,
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function checkNotFoundValidation(Validator $validator): void
    {
        $failedRules = $validator->failed();

        foreach ($this->notFoundKeys as $field => $message) {
            if (isset($failedRules[$field]['Exists'])) {
                throw new HttpResponseException(
                    response()->json([
                        'code' => Response::HTTP_NOT_FOUND,
                        'message' => $message,
                    ], Response::HTTP_NOT_FOUND)
                );
            }
        }
    }
}
