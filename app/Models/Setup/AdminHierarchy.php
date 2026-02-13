<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminHierarchy extends Model
{
    use SoftDeletes;

    public $table = 'admin_areas';

    public $fillable = [
        'name',
        'area_code',
        'area_type_id',
        'parent_area_id',
        'retired',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'area_code' => 'string',
        'area_type_id' => 'integer',
        'parent_area_id' => 'integer',
        'retired' => 'boolean',
        'created_by_user_id' => 'integer',
        'updated_by_user_id' => 'integer',
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
        'area_code' => 'nullable|string|max:8',
        'area_type_id' => 'required|integer|exists:admin_area_levels,id',
        'parent_area_id' => 'nullable|integer|exists:admin_areas,id',
        'retired' => 'sometimes|boolean',
        'created_by_user_id' => 'nullable|integer|exists:users,id',
        'updated_by_user_id' => 'nullable|integer|exists:users,id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_area_id', 'id');
    }

    public function areaType(): BelongsTo
    {
        return $this->belongsTo(AdminAreaLevel::class, 'area_type_id', 'id');
    }
}
