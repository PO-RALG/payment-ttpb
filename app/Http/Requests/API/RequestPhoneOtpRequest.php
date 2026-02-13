<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RequestPhoneOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
