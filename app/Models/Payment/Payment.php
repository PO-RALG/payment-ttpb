<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'uuid',
        'invoice_id',
        'bill_id',
        'transaction_id',
        'pay_ref_id',
        'billed_amount',
        'paid_amount',
        'currency',
        'status',
        'payer_name',
        'payer_phone',
        'payer_email',
        'paid_at',
        'raw_payload',
        'failure_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'billed_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
