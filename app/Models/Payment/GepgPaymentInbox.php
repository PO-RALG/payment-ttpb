<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GepgPaymentInbox extends Model
{
    use HasFactory;

    protected $table = 'gepg_payment_inbox';

    protected $fillable = [
        'external_request_id',
        'transaction_id',
        'bill_id',
        'grp_bill_id',
        'control_number',
        'signature',
        'payload',
        'verified',
        'processed',
        'attempt_count',
        'received_at',
        'processed_at',
        'last_error',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'processed' => 'boolean',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
