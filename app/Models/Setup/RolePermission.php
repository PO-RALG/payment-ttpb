<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use SoftDeletes;

    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'role_id' => 'integer',
        'permission_id' => 'integer',
        'active' => 'boolean',
    ];

    public static array $rules = [];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
