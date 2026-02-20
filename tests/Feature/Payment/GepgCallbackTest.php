<?php

namespace Tests\Feature\Payment;

use App\Models\Payment\Bill;
use App\Models\Payment\Invoice;
use App\Models\Payment\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GepgCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_payment_is_rejected(): void
    {
        [$invoice, $bill] = $this->seedInvoiceAndBill();

        $xml = $this->paymentXml([
            'ReqId' => 'SP-REQ-1',
            'GrpBillId' => $bill->grp_bill_id,
            'BillId' => $bill->bill_reference,
            'BillCtrNum' => '12345678901',
            'BillAmt' => '15000.00',
            'PaidAmt' => '10000.00',
            'TrxId' => 'TRX-1001',
            'PayRefId' => 'RCPT-1001',
        ]);

        $response = $this->call('POST', '/api/v1/gepg/callback/payment-response', [], [], [], [
            'CONTENT_TYPE' => 'application/xml',
            'HTTP_ACCEPT' => 'application/xml',
        ], $xml);

        $response->assertOk();

        $payment = Payment::query()->where('transaction_id', 'TRX-1001')->first();
        $this->assertNotNull($payment);
        $this->assertSame('REJECTED', $payment->status);
        $this->assertSame('FULL_PAYMENT_REQUIRED', $payment->failure_reason);

        $this->assertSame('ISSUED', $invoice->fresh()->status);
        $this->assertSame('CONTROL_NUMBER_ISSUED', $bill->fresh()->status);
    }

    public function test_full_payment_marks_invoice_and_bill_paid(): void
    {
        [$invoice, $bill] = $this->seedInvoiceAndBill();

        $xml = $this->paymentXml([
            'ReqId' => 'SP-REQ-2',
            'GrpBillId' => $bill->grp_bill_id,
            'BillId' => $bill->bill_reference,
            'BillCtrNum' => '12345678901',
            'BillAmt' => '15000.00',
            'PaidAmt' => '15000.00',
            'TrxId' => 'TRX-1002',
            'PayRefId' => 'RCPT-1002',
        ]);

        $response = $this->call('POST', '/api/v1/gepg/callback/payment-response', [], [], [], [
            'CONTENT_TYPE' => 'application/xml',
            'HTTP_ACCEPT' => 'application/xml',
        ], $xml);

        $response->assertOk();

        $payment = Payment::query()->where('transaction_id', 'TRX-1002')->first();
        $this->assertNotNull($payment);
        $this->assertSame('POSTED', $payment->status);

        $this->assertSame('PAID', $invoice->fresh()->status);
        $this->assertSame('PAID', $bill->fresh()->status);
    }

    private function seedInvoiceAndBill(): array
    {
        $invoice = Invoice::query()->create([
            'uuid' => (string) Str::uuid(),
            'invoice_number' => 'INV-TEST-' . Str::random(6),
            'module' => 'REGISTRATION',
            'sub_module' => 'Full Registration',
            'payer_name' => 'Test User',
            'payer_phone' => '255700000000',
            'payer_email' => 'test@example.com',
            'amount_total' => 15000,
            'currency' => 'TZS',
            'status' => 'ISSUED',
        ]);

        $bill = Bill::query()->create([
            'uuid' => (string) Str::uuid(),
            'invoice_id' => $invoice->id,
            'bill_reference' => 'BILL-TEST-' . Str::random(6),
            'req_id' => 'SP' . now()->format('YmdHisv'),
            'grp_bill_id' => 'GRP-TEST-' . Str::random(6),
            'control_number' => '12345678901',
            'amount' => 15000,
            'currency' => 'TZS',
            'status' => 'CONTROL_NUMBER_ISSUED',
        ]);

        return [$invoice, $bill];
    }

    private function paymentXml(array $overrides = []): string
    {
        $data = array_merge([
            'ReqId' => 'SP-REQ-1',
            'GrpBillId' => 'GRP-BILL',
            'BillId' => 'BILL-1',
            'BillCtrNum' => '12345678901',
            'BillAmt' => '15000.00',
            'PaidAmt' => '15000.00',
            'TrxId' => 'TRX-1',
            'PayRefId' => 'RCPT-1',
            'Ccy' => 'TZS',
            'PyrName' => 'Payer Name',
            'PyrCellNum' => '255700000000',
            'PyrEmail' => 'payer@example.com',
            'TrxDtTm' => now()->format('Y-m-d\\TH:i:s'),
        ], $overrides);

        return '<Gepg><pmtSpNtfReq>'
            . '<ReqId>' . $data['ReqId'] . '</ReqId>'
            . '<GrpBillId>' . $data['GrpBillId'] . '</GrpBillId>'
            . '<BillId>' . $data['BillId'] . '</BillId>'
            . '<BillCtrNum>' . $data['BillCtrNum'] . '</BillCtrNum>'
            . '<BillAmt>' . $data['BillAmt'] . '</BillAmt>'
            . '<PaidAmt>' . $data['PaidAmt'] . '</PaidAmt>'
            . '<TrxId>' . $data['TrxId'] . '</TrxId>'
            . '<PayRefId>' . $data['PayRefId'] . '</PayRefId>'
            . '<Ccy>' . $data['Ccy'] . '</Ccy>'
            . '<PyrName>' . $data['PyrName'] . '</PyrName>'
            . '<PyrCellNum>' . $data['PyrCellNum'] . '</PyrCellNum>'
            . '<PyrEmail>' . $data['PyrEmail'] . '</PyrEmail>'
            . '<TrxDtTm>' . $data['TrxDtTm'] . '</TrxDtTm>'
            . '</pmtSpNtfReq><signature>dummy-signature</signature></Gepg>';
    }
}
