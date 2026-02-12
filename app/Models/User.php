<?php

namespace App\Models;

use App\Models\Setup\AdminHierarchy;
use App\Models\Setup\Gender;
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
        'date_of_birth',
        'gender_id',
        'phone',
        'admin_hierarchy_id',
        'post_code',
        'physical_address',
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
        'date_of_birth' => 'date',
        'gender_id' => 'integer',
        'admin_hierarchy_id' => 'integer',
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

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function adminHierarchy()
    {
        return $this->belongsTo(AdminHierarchy::class);
    }
}
