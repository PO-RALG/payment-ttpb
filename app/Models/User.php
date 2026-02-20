<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name
        );
    }
}
