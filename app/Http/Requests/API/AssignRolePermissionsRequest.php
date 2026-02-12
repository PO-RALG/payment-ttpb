<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AssignRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $intMax = 2147483647;

        return [
            'role_id' => ['bail', 'required', 'integer', 'min:1', 'max:' . $intMax, 'exists:roles,id'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['bail', 'integer', 'min:1', 'max:' . $intMax, 'exists:permissions,id'],
        ];
    }
}
