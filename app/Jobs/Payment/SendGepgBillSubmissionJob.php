<?php

namespace App\Jobs\Payment;

use App\Services\Payment\GepgSubmissionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendGepgBillSubmissionJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $backoff = 15;

    public function __construct(public readonly int $gepgRequestId)
    {
    }

    public function handle(GepgSubmissionService $service): void
    {
        $service->submitBillRequest($this->gepgRequestId);
    }
}
