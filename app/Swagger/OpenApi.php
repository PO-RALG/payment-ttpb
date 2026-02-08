<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'TTPB API',
    description: 'API documentation for the TTPB backend services.'
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'Primary API host'
)]
class OpenApi
{
}
