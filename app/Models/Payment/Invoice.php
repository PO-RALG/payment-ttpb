<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = [
        'uuid',
        'invoice_number',
        'trigger_code',
        'trigger_reference',
        'module',
        'sub_module',
        'payer_name',
        'payer_phone',
        'payer_email',
        'amount_total',
        'currency',
        'status',
        'expires_at',
        'meta',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount_total' => 'decimal:2',
        'expires_at' => 'datetime',
        'meta' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
