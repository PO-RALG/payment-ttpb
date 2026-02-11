<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
     use SoftDeletes;    public $table = 'institutions';

    public $fillable = [
        'name',
        'institution_code',
        'registration_no',
        'tin',
        'institution_type',
        'region',
        'district',
        'ward',
        'address',
        'website',
        'status',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'institution_code' => 'string',
        'registration_no' => 'string',
        'tin' => 'string',
        'institution_type' => 'string',
        'region' => 'string',
        'district' => 'string',
        'ward' => 'string',
        'address' => 'string',
        'website' => 'string',
        'status' => 'string',
        'created_by_user_id' => 'integer'
    ];

    public static array $rules = [
        'name' => 'required',
        'institution_code' => 'required|unique:institutions,institution_code',
        'institution_type' => 'required',
        'region' => 'required',
        'website' => 'nullable|url',
        'status' => 'required'
    ];



}
