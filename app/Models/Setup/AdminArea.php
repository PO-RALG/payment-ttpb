<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminArea extends Model
{
    use SoftDeletes;

    protected $table = 'admin_areas';

    protected $fillable = [
        'id',
        'name',
        'parent_area_id',
        'description',
        'boundary_id',
        'valid_from',
        'valid_until',
        'area_type_id',
        'created_on',
        'created_by_user_id',
        'updated_on',
        'updated_by_user_id',
        'boundary_status_id',
        'retired',
        'label',
        'area_short_name',
        'area_hq_id',
        'area_code',
        'establishment_date_approximated',
        'mof_code',
        'ares_code',
    ];

    protected $casts = [
        'id' => 'integer',
        'parent_area_id' => 'integer',
        'boundary_id' => 'integer',
        'area_type_id' => 'integer',
        'created_by_user_id' => 'integer',
        'updated_by_user_id' => 'integer',
        'boundary_status_id' => 'integer',
        'retired' => 'boolean',
        'area_hq_id' => 'integer',
        'establishment_date_approximated' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_area_id');
    }

    public function headquarters(): BelongsTo
    {
        return $this->belongsTo(self::class, 'area_hq_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_area_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(AdminAreaLevel::class, 'area_type_id');
    }
}
