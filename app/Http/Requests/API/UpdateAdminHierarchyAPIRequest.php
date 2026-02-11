<?php

namespace App\Http\Requests\API;

use App\Common\Requests\APIRequest;
use App\Models\Setup\AdminHierarchy;

class UpdateAdminHierarchyAPIRequest extends APIRequest
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
        $rules = AdminHierarchy::$rules;
        $rules['name'] = $rules['name'].",".$this->route("admin_hierarchy");$rules['code'] = $rules['code'].",".$this->route("admin_hierarchy");
        return $rules;
    }
}
