<?php

namespace App\Models;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\Setup\AdminHierarchy;
use App\Models\Setup\Gender;
use App\Models\Setup\Nationality;
use App\Models\Setup\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'nationality_id',
        'nin',
        'phone',
        'admin_area_id',
        'is_active',
        'post_code',
        'physical_address',
        'password',
        'first_login_at',
        'must_change_password',
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
        'nationality_id' => 'integer',
        'admin_area_id' => 'integer',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'first_login_at' => 'datetime',
        'must_change_password' => 'boolean',
        'password' => 'hashed',
    ];

    protected $attributes = [
        'is_active' => true,
        'must_change_password' => true,
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

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function adminHierarchy(): BelongsTo
    {
        return $this->belongsTo(AdminHierarchy::class, 'admin_area_id');
    }

    public function phoneOtps(): HasMany
    {
        return $this->hasMany(PhoneOtp::class);
    }

    public function emailOtps(): HasMany
    {
        return $this->hasMany(EmailOtp::class);
    }
}
