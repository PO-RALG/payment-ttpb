<?php

namespace App\Http\Requests\API\Setup;

use App\Models\Setup\Permission;
use App\Common\Requests\APIRequest;

class UpdatePermissionAPIRequest extends APIRequest
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
        $rules = Permission::$rules;
        $rules['name'] = $rules['name'].",".$this->route("permission");$rules['code'] = $rules['code'].",".$this->route("permission");
        return $rules;
    }
}
