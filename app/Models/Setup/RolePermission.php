<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
class RolePermission extends Model
{
     use SoftDeletes;    public $table = 'role_permissions';

    public $fillable = [
        'id',
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

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\Role::class, 'role_id', 'id');
    }

    public function permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\Permission::class, 'permission_id', 'id');
    }

}
