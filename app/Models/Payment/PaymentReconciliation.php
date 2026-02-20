<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReconciliation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payment_reconciliations';

    protected $fillable = [
        'reconciliation_date',
        'reference',
        'total_records',
        'matched_records',
        'unmatched_records',
        'details',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'reconciliation_date' => 'date',
    ];
}
