<?php

namespace App\Jobs\Payment;

use App\Models\Payment\GepgPaymentInbox;
use App\Services\Payment\GepgInboxProcessorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessPaymentInboxJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 10;

    public int $backoff = 10;

    public function __construct(public readonly int $inboxId)
    {
    }

    public function handle(GepgInboxProcessorService $service): void
    {
        GepgPaymentInbox::query()->whereKey($this->inboxId)->increment('attempt_count');
        $service->processPaymentInbox($this->inboxId);
    }

    public function failed(?Throwable $exception): void
    {
        GepgPaymentInbox::query()->whereKey($this->inboxId)->update([
            'last_error' => $exception?->getMessage(),
        ]);
    }
}
