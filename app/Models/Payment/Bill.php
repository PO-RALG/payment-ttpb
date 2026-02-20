<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bills';

    protected $fillable = [
        'uuid',
        'invoice_id',
        'bill_reference',
        'req_id',
        'grp_bill_id',
        'control_number',
        'amount',
        'currency',
        'status',
        'expires_at',
        'submit_payload',
        'submit_response',
        'meta',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'meta' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
