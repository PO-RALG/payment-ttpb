<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Health', description: 'Health check utilities')]
class HealthController extends Controller
{
    #[OA\Get(
        path: '/api/health',
        summary: 'Check API availability',
        description: 'Returns a simple heartbeat payload to confirm the API is online.',
        tags: ['Health']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful heartbeat',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'ok!'),
            ]
        )
    )]
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => 'ok!']);
    }
}
