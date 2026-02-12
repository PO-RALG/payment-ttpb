<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
     use SoftDeletes;    public $table = 'institutions';

    public $fillable = [
        'programme_code',
        'programme_name',
        'is_teaching_professional_program',
        'active'
    ];

    protected $casts = [
        'id' => 'integer',
        'programme_code' => 'string',
        'programme_name' => 'string',
        'is_teaching_professional_program' => 'boolean'
    ];

    public static array $rules = [

    ];

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\users::class, 'created_by', 'id');
    }

}
