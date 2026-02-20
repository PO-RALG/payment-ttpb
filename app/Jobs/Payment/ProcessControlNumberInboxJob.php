<?php

namespace App\Jobs\Payment;

use App\Models\Payment\GepgControlNumberInbox;
use App\Services\Payment\GepgInboxProcessorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessControlNumberInboxJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 10;

    public int $backoff = 10;

    public function __construct(public readonly int $inboxId)
    {
    }

    public function handle(GepgInboxProcessorService $service): void
    {
        GepgControlNumberInbox::query()->whereKey($this->inboxId)->increment('attempt_count');
        $service->processControlNumberInbox($this->inboxId);
    }

    public function failed(?Throwable $exception): void
    {
        GepgControlNumberInbox::query()->whereKey($this->inboxId)->update([
            'last_error' => $exception?->getMessage(),
        ]);
    }
}
