<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHierarchySection extends Model
{
     use SoftDeletes;    public $table = 'admin_hierarchy_sections';

    public $fillable = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [

    ];

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\sections::class, 'section_id', 'id');
    }

    public function adminHierarchy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\AdminHierarchy::class, 'admin_area_id', 'id');
    }

}
