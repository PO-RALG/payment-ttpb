<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationType extends Model
{
     use SoftDeletes;    public $table = 'registration_types';

    public $fillable = [
        'id',
        'code',
        'name',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    public static array $rules = [
        'code' => 'required',
        'name' => 'required'
    ];



}
