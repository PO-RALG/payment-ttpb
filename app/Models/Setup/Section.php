<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
     use SoftDeletes;    public $table = 'sections';

    public $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'active' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'code' => 'required|unique:sections',
        'name' => 'required|unique:sections'
    ];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\Section::class, 'parent_id', 'id');
    }

}
