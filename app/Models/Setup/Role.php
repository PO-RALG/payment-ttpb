<?php

namespace App\Models\Setup;

use App\Models\Setup\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'code',
        'created_by',
        'updated_by',
        'active',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'active' => 'boolean',
    ];

    public static array $rules = [
        'name' => 'required|unique:roles',
        'code' => 'required|unique:roles',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_at'])
            ->wherePivotNull('deleted_at')
            ->whereNull('permissions.deleted_at');
    }
}
