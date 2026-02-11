<?php

namespace App\Http\Requests\API\Setup;

use App\Common\Requests\APIRequest;
use App\Models\Setup\AdminHierarchyLevel;

class UpdateAdminHierarchyLevelAPIRequest extends APIRequest
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
        $rules = AdminHierarchyLevel::$rules;
        $rules['code'] = $rules['code'].",".$this->route("admin_hierarchy_level");$rules['name'] = $rules['name'].",".$this->route("admin_hierarchy_level");
        return $rules;
    }
}
