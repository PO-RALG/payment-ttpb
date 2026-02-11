<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHierarchyLevel extends Model
{
     use SoftDeletes;    public $table = 'admin_hierarchy_levels';

    public $fillable = [
        'code',
        'name',
        'position',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'position' => 'integer',
        'active' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'code' => 'required|unique:admin_hierarchy_levels',
        'name' => 'required|unique:admin_hierarchy_levels',
        'position' => 'required',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];



}
