<?php

namespace App\Swagger\Endpoints;

use OpenApi\Attributes as OA;

class HealthEndpoint
{
    #[OA\Get(
        path: "/api/v1/health",
        tags: ["Health"],
        summary: "API heartbeat",
        operationId: "getHealthStatus",
        responses: [
            new OA\Response(response: 200, description: "Service healthy", content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string", example: "ok!"),
                ],
                type: "object"
            )),
        ]
    )]
    public static function document(): void {}
}
