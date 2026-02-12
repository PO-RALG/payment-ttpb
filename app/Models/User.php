<?php

namespace App\Models;

use App\Models\Setup\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'admin_hierarchy_id',
        'password',
    ];

    /**
     * Attributes hidden from JSON responses
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Accessor: full name
     */
    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps()
            ->withPivot(['assigned_at', 'assigned_by_user_id'])
            ->wherePivotNull('deleted_at')
            ->whereNull('roles.deleted_at');
    }
}
