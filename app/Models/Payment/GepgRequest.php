<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GepgRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'gepg_requests';

    protected $fillable = [
        'invoice_id',
        'bill_id',
        'request_type',
        'request_id',
        'payload',
        'signature',
        'status',
        'response_payload',
        'created_by',
        'updated_by',
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
