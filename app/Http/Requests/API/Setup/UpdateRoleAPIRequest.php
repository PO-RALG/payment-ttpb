<?php

namespace App\Http\Requests\API\Setup;

use App\Models\Setup\Role;
use App\Common\Requests\APIRequest;

class UpdateRoleAPIRequest extends APIRequest
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
        $rules = Role::$rules;
        $rules['name'] = $rules['name'].",".$this->route("role");$rules['code'] = $rules['code'].",".$this->route("role");
        return $rules;
    }
}
