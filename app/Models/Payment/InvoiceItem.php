<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'fee_rule_id',
        'item_code',
        'description',
        'quantity',
        'unit_amount',
        'line_total',
        'meta',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function feeRule(): BelongsTo
    {
        return $this->belongsTo(FeeRule::class);
    }
}
