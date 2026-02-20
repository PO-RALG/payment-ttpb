<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GepgControlNumberInbox extends Model
{
    use HasFactory;

    protected $table = 'gepg_control_number_inbox';

    protected $fillable = [
        'external_request_id',
        'bill_id',
        'grp_bill_id',
        'control_number',
        'status_code',
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
