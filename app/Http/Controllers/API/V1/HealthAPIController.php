<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HealthAPIController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => 'ok!']);
    }
}
