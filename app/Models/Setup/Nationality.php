<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
class Nationality extends Model
{
     use SoftDeletes;    public $table = 'nationalities';

    public $fillable = [
        'id',
        'code',
        'iso3_code',
        'phone_code',
        'name',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'iso3_code' => 'string',
        'phone_code' => 'string',
        'name' => 'string',
        'is_active' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'code' => 'required',
        'iso3_code' => 'required',
        'phone_code' => 'required',
        'name' => 'required',
        'is_active' => 'required'
    ];

    

}
