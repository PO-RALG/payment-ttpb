<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class AdminAreaLevel extends Model
{
    protected $table = 'admin_area_levels';

    protected $fillable = [
        'name',
        'name_sw',
        'order_id',
    ];
}
