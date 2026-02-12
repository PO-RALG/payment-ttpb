<?php

namespace App\Swagger\Endpoints;

use OpenApi\Attributes as OA;

class AuthEndpoints
{
    #[OA\Post(
        path: "/api/auth/login",
        tags: ["Auth"],
        summary: "Authenticate and obtain tokens",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["identifier", "password"],
                properties: [
                    new OA\Property(property: "identifier", type: "string", example: "demo@ttpb.local"),
                    new OA\Property(property: "password", type: "string", example: "ChangeMe123!"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Authenticated", content: new OA\JsonContent(ref: "#/components/schemas/TokenResponse")),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
    public static function login(): void {}

    #[OA\Post(
        path: "/api/auth/refresh",
        tags: ["Auth"],
        summary: "Refresh an access token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["refresh_token"],
                properties: [
                    new OA\Property(property: "refresh_token", type: "string", example: "long-refresh-token"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Tokens refreshed", content: new OA\JsonContent(ref: "#/components/schemas/TokenResponse")),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
    public static function refresh(): void {}

    #[OA\Post(
        path: "/api/auth/logout",
        tags: ["Auth"],
        summary: "Logout current session",
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logged out", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function logout(): void {}

    #[OA\Post(
        path: "/api/auth/users",
        tags: ["Auth"],
        summary: "Create a new user",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name", "last_name", "admin_hierarchy_id", "email", "phone"],
                properties: [
                    new OA\Property(property: "first_name", type: "string"),
                    new OA\Property(property: "middle_name", type: "string", nullable: true),
                    new OA\Property(property: "last_name", type: "string"),
                    new OA\Property(property: "date_of_birth", type: "string", format: "date", nullable: true),
                    new OA\Property(property: "gender_id", type: "integer", nullable: true),
                    new OA\Property(property: "admin_hierarchy_id", type: "integer"),
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(
                        property: "roles",
                        type: "array",
                        items: new OA\Items(type: "object", properties: [
                            new OA\Property(property: "id", type: "integer"),
                        ])
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "User created", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
    public static function createUser(): void {}

    #[OA\Post(
        path: "/api/auth/users/{user}/deactivate",
        tags: ["Auth"],
        summary: "Deactivate or reactivate a user",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "active", type: "boolean", example: false),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: "Status updated", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 404, description: "User not found"),
        ]
    )]
    public static function toggleUserStatus(): void {}

    #[OA\Post(
        path: "/api/auth/users/{user}/reset-password",
        tags: ["Auth"],
        summary: "Reset user password",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "user", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Password reset", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 404, description: "User not found"),
        ]
    )]
    public static function resetPassword(): void {}

    #[OA\Post(
        path: "/api/auth/change-password",
        tags: ["Auth"],
        summary: "Change current user password",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["new_password"],
                properties: [
                    new OA\Property(property: "current_password", type: "string", nullable: true),
                    new OA\Property(property: "new_password", type: "string", minLength: 8),
                    new OA\Property(property: "new_password_confirmation", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Password changed", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
    public static function changePassword(): void {}
}
