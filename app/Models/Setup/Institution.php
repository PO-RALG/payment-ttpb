<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Institution extends Model
{
    use SoftDeletes;

    protected $table = 'institutions';

    protected $fillable = [
        'programme_code',
        'programme_name',
        'is_teaching_professional_program',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'programme_code' => 'string',
        'programme_name' => 'string',
        'is_teaching_professional_program' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * User who created the record
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the record
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
