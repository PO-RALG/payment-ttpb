<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\GepgCallbackService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GepgCallbackAPIController extends Controller
{
    public function __construct(private readonly GepgCallbackService $service)
    {
    }

    public function billResponse(Request $request): Response
    {
        $xml = $request->getContent();
        $ack = $this->service->handleBillResponse($xml);

        return response($ack, 200)->header('Content-Type', 'application/xml');
    }

    public function paymentResponse(Request $request): Response
    {
        $xml = $request->getContent();
        $ack = $this->service->handlePaymentResponse($xml);

        return response($ack, 200)->header('Content-Type', 'application/xml');
    }
}
