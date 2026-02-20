<?php

namespace App\Services\Payment;

use App\Models\Payment\Bill;
use App\Models\Payment\GepgRequest;
use Illuminate\Support\Facades\Http;
use Throwable;

class GepgSubmissionService
{
    public function submitBillRequest(int $gepgRequestId): void
    {
        $request = GepgRequest::query()->with('bill')->findOrFail($gepgRequestId);
        $bill = $request->bill;

        if (! $bill instanceof Bill) {
            $request->status = 'FAILED';
            $request->response_payload = 'Bill not found for GePG request.';
            $request->save();

            return;
        }

        $payload = (string) $bill->submit_payload;
        if ($payload === '') {
            $request->status = 'FAILED';
            $request->response_payload = 'Empty payload.';
            $request->save();

            return;
        }

        try {
            $response = Http::timeout(30)
                ->accept('application/xml')
                ->withHeaders([
                    'Content-Type' => 'application/xml',
                    'X-GePG-Code' => (string) config('gepg.sp_code'),
                    'X-GePG-Com' => (string) config('gepg.company_code'),
                    'X-GePG-Alg' => (string) config('gepg.algorithm'),
                    'X-System-Code' => (string) config('gepg.system_code'),
                ])
                ->withBody($payload, 'application/xml')
                ->post($this->submissionUrl());

            $request->status = $response->successful() ? 'SENT' : 'FAILED';
            $request->response_payload = $response->body();
            $request->save();

            $bill->submit_response = $response->body();
            $bill->status = $response->successful() ? 'SUBMITTED' : 'FAILED';
            $bill->save();
        } catch (Throwable $e) {
            $request->status = 'FAILED';
            $request->response_payload = $e->getMessage();
            $request->save();

            $bill->submit_response = $e->getMessage();
            $bill->status = 'FAILED';
            $bill->save();

            throw $e;
        }
    }

    private function submissionUrl(): string
    {
        $base = rtrim((string) config('gepg.endpoint_url'), '/');
        $path = '/' . ltrim((string) config('gepg.bill_submission_path', '/api/bill/20/submission'), '/');

        return $base . $path;
    }
}
