<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RequestEmailOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string', 'email', 'max:150'],
        ];
    }
}
