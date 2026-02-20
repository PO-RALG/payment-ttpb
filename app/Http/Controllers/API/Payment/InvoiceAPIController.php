<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Payment\CreateBillRequest;
use App\Models\Payment\Invoice;
use App\Services\Payment\InvoiceBillingService;
use Illuminate\Http\JsonResponse;

class InvoiceAPIController extends Controller
{
    public function __construct(private readonly InvoiceBillingService $service)
    {
    }

    public function createBill(CreateBillRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $expiresAt = $validated['expires_at'] ?? null;
        unset($validated['expires_at']);

        $invoice = $this->service->createInvoice($validated);
        $bill = $this->service->issueBill($invoice, $expiresAt);

        $bill->load(['invoice.items']);

        return response()->json([
            'success' => true,
            'message' => 'Bill created successfully.',
            'data' => $bill,
        ], 201);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['items', 'bills.payments']);

        return response()->json([
            'success' => true,
            'message' => 'Invoice retrieved successfully.',
            'data' => $invoice,
        ]);
    }
}
