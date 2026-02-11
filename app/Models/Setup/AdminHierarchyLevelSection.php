<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHierarchyLevelSection extends Model
{
     use SoftDeletes;    public $table = 'admin_hierarchy_level_sections';

    public $fillable = [
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [

    ];

    public function adminHierarchyLevel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\AdminHierarchyLevel::class, 'admin_hierarchy_level_id', 'id');
    }

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id', 'id');
    }

}
