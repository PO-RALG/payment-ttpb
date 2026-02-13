<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationUnit extends Model
{
     use SoftDeletes;    public $table = 'organization_units';

    public $fillable = [
        'id',
        'name',
        'level',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'level' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'name' => 'required',
        'level' => 'required'
    ];

}
