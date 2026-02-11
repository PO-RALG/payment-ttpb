<?php

namespace App\Http\Requests\API\Setup;

use App\Common\Requests\APIRequest;
use App\Models\Setup\UserRole;

class CreateUserRoleAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return UserRole::$rules;
    }
}
