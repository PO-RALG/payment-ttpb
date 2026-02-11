<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Tanzania Teacher Registration Board API",
    description: "Backend API documentation"
)]
#[OA\Server(url: "/api/v1", description: "API v1")]
class OpenApi {}