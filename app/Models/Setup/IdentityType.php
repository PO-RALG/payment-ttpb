<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdentityType extends Model
{
     use SoftDeletes;    public $table = 'identity_types';

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

    public function organizationType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\OrganizationType::class, 'organization_type_id', 'id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\OrganizationUnit::class, 'parent_id', 'id');
    }

}
