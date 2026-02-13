<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'gender_id' => ['nullable', 'integer', 'exists:genders,id'],
            'admin_area_id' => ['required', 'integer', 'exists:admin_areas,id'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'post_code' => ['nullable', 'string', 'max:20'],
            'physical_address' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*.id' => ['required_with:roles', 'integer', 'exists:roles,id'],
        ];
    }
}
