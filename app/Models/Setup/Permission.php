<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
class Permission extends Model
{
     use SoftDeletes;    public $table = 'permissions';

    public $fillable = [
        'name',
        'code',
        'created_by',
        'updated_by',
        'active',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'active' => 'boolean'
    ];

    public static array $rules = [
        'name' => 'required|unique:roles',
        'code' => 'required|unique:roles'
    ];

    

}
