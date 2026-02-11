<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHierarchy extends Model
{
     use SoftDeletes;    public $table = 'admin_hierarchies';

    public $fillable = [
        'name',
        'code',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'active' => 'boolean'
    ];

    public static array $rules = [
        'name' => 'required|unique:admin_hierarchies',
        'code' => 'required|unique:admin_hierarchies'
    ];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\AdminHierarchy::class, 'parent_id', 'id');
    }

    public function adminHierarchyPosition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\AdminHierarchyLevel::class, 'admin_hierarchy_position', 'position');
    }

}
