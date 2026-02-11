<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
     use SoftDeletes;    public $table = 'user_roles';

    public $fillable = [
        'id',
        'assigned_at',
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\users::class, 'user_id', 'id');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\roles::class, 'role_id', 'id');
    }

    public function assignedByUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\users::class, 'assigned_by_user_id', 'id');
    }

}
