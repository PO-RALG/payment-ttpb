<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserIdentifier extends Model
{
     use SoftDeletes;    public $table = 'user_identifiers';

    public $fillable = [
        'id',
        'value',
        'is_primary',
        'verified',
        'verified_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'value' => 'string',
        'is_primary' => 'boolean',
        'verified' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'value' => 'required',
        'is_primary' => 'required',
        'verified' => 'required',
        'verified_at' => 'required'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function identityType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Setup\IdentityType::class, 'identity_type_id', 'id');
    }

}
