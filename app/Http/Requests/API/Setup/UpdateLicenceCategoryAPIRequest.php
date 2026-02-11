<?php

namespace App\Http\Requests\API\Setup;

use App\Common\Requests\APIRequest;
use App\Models\Setup\LicenceCategory;

class UpdateLicenceCategoryAPIRequest extends APIRequest
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
        $rules = LicenceCategory::$rules;

        return $rules;
    }
}
