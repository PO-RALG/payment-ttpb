<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GepgCallback extends Model
{
    use HasFactory;

    protected $table = 'gepg_callbacks';

    protected $fillable = [
        'callback_type',
        'external_request_id',
        'transaction_id',
        'bill_reference',
        'control_number',
        'signature',
        'payload',
        'verified',
        'processed',
        'processed_at',
        'status_code',
        'status_message',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];
}
