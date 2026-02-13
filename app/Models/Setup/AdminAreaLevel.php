<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminAreaLevel extends Model
{
    use SoftDeletes;

    public const LEVEL_COUNTRY = 1;
    public const LEVEL_REGION = 2;
    public const LEVEL_COUNCIL = 3;
    public const LEVEL_WARD = 4;

    protected $table = 'admin_area_levels';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'name_sw',
        'order_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
    ];

    public function areas(): HasMany
    {
        return $this->hasMany(AdminArea::class, 'area_type_id');
    }
}
