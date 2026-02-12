<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StandardResponse",
    required: ["success", "message"],
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(property: "message", type: "string", example: "Operation completed successfully."),
        new OA\Property(property: "data", type: "object", nullable: true),
    ]
)]
#[OA\Schema(
    schema: "PaginatedResponse",
    properties: [
        new OA\Property(property: "success", type: "boolean", example: true),
        new OA\Property(property: "message", type: "string", example: "Records retrieved successfully."),
        new OA\Property(
            property: "data",
            type: "array",
            items: new OA\Items(type: "object")
        ),
        new OA\Property(
            property: "meta",
            properties: [
                new OA\Property(property: "current_page", type: "integer", example: 1),
                new OA\Property(property: "per_page", type: "integer", example: 15),
                new OA\Property(property: "total", type: "integer", example: 120),
                new OA\Property(property: "last_page", type: "integer", example: 8),
            ],
            type: "object"
        ),
    ]
)]
#[OA\Schema(
    schema: "UserResource",
    required: ["id", "first_name", "last_name", "email", "phone"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 10),
        new OA\Property(property: "first_name", type: "string", example: "Jane"),
        new OA\Property(property: "middle_name", type: "string", nullable: true, example: "C"),
        new OA\Property(property: "last_name", type: "string", example: "Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "jane.doe@ttpb.local"),
        new OA\Property(property: "phone", type: "string", example: "255700000000"),
        new OA\Property(property: "roles", type: "array", items: new OA\Items(ref: "#/components/schemas/RoleResource")),
    ]
)]
#[OA\Schema(
    schema: "RoleResource",
    required: ["id", "name", "code"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 3),
        new OA\Property(property: "name", type: "string", example: "Registrar"),
        new OA\Property(property: "code", type: "string", example: "REG"),
        new OA\Property(property: "permissions", type: "array", items: new OA\Items(ref: "#/components/schemas/PermissionResource")),
    ]
)]
#[OA\Schema(
    schema: "PermissionResource",
    required: ["code"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 12),
        new OA\Property(property: "code", type: "string", example: "TEACHER_APPROVE"),
        new OA\Property(property: "name", type: "string", example: "Approve teachers"),
    ]
)]
#[OA\Schema(
    schema: "TokenResponse",
    required: ["access_token", "token_type", "expires_in", "refresh_token", "refresh_expires_in", "user"],
    properties: [
        new OA\Property(property: "access_token", type: "string", example: "1|U1RZa..."),
        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
        new OA\Property(property: "expires_in", type: "integer", example: 14400),
        new OA\Property(property: "refresh_token", type: "string", example: "long-refresh-token"),
        new OA\Property(property: "refresh_expires_in", type: "integer", example: 86400),
        new OA\Property(property: "user", ref: "#/components/schemas/UserResource"),
    ]
)]
class Schemas {}
