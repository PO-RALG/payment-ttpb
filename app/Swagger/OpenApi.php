<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Tanzania Teacher Registration Board API",
    description: "Backend API documentation"
)]
#[OA\Server(url: "/", description: "Base API path")]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Token",
    description: "Provide the Sanctum-issued bearer token."
)]
class OpenApi {}
