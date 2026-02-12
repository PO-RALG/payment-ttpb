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
        $roleId = $this->route('role');

        return [
            'name' => 'required|unique:roles,name,' . $roleId,
            'code' => 'required|unique:roles,code,' . $roleId,
        ];
    }
}
