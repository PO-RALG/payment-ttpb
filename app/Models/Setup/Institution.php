<?php

namespace App\Models\Setup;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    public $table = 'institutions';

    public $fillable = [
        'name',
        'institution_code',
        'registration_no',
        'tin',
        'institution_type',
        'region',
        'district',
        'ward',
        'address',
        'website',
        'created_by_user_id',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'institution_code' => 'string',
        'registration_no' => 'string',
        'tin' => 'string',
        'institution_type' => 'string',
        'region' => 'string',
        'district' => 'string',
        'ward' => 'string',
        'address' => 'string',
        'website' => 'string',
        'created_by_user_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
        'institution_code' => 'required|string|max:255',
        'registration_no' => 'required|string|max:255',
        'tin' => 'required|string|max:255',
        'institution_type' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'district' => 'required|string|max:255',
        'ward' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'website' => 'required|string|max:255',
        'created_by_user_id' => 'required|integer',
        'is_active' => 'sometimes|boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }
}
