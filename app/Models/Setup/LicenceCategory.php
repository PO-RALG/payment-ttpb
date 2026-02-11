<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenceCategory extends Model
{
     use SoftDeletes;    public $table = 'licence_categories';

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
