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
                required: ["first_name", "last_name", "admin_area_id", "email", "phone"],
                properties: [
                    new OA\Property(property: "first_name", type: "string"),
                    new OA\Property(property: "middle_name", type: "string", nullable: true),
                    new OA\Property(property: "last_name", type: "string"),
                    new OA\Property(property: "date_of_birth", type: "string", format: "date", nullable: true),
                    new OA\Property(property: "gender_id", type: "integer", nullable: true),
                    new OA\Property(property: "admin_area_id", type: "integer"),
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

    #[OA\Post(
        path: "/api/auth/phone-otp/request",
        tags: ["Auth"],
        summary: "Request phone verification OTP",
        description: "Generates a temporary phone verification code (mocked send) and returns the remaining lifetime in seconds.",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", nullable: true, example: "255700000000"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP issued",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Phone OTP requested successfully."),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "phone", type: "string", example: "255700000000"),
                                new OA\Property(property: "expires_in_seconds", type: "integer", example: 300),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public static function requestPhoneOtp(): void {}

    #[OA\Post(
        path: "/api/auth/phone-otp/verify",
        tags: ["Auth"],
        summary: "Verify phone OTP",
        description: "Confirms the latest OTP code and stamps the user's phone as verified.",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["code"],
                properties: [
                    new OA\Property(property: "code", type: "string", example: "1234"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Phone verified",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Phone number verified successfully."),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "phone_verified_at", type: "string", format: "date-time", example: "2026-02-13T12:00:00Z"),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 422, description: "Invalid or expired code"),
        ]
    )]
    public static function verifyPhoneOtp(): void {}

    #[OA\Post(
        path: "/api/auth/email-otp/request",
        tags: ["Auth"],
        summary: "Request email verification OTP",
        description: "Generates a temporary email verification code (mock send) and returns how long it is valid.",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", nullable: true, example: "user@ttpb.local"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP issued",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Email OTP requested successfully."),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "email", type: "string", example: "user@ttpb.local"),
                                new OA\Property(property: "expires_in_seconds", type: "integer", example: 300),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public static function requestEmailOtp(): void {}

    #[OA\Post(
        path: "/api/auth/email-otp/verify",
        tags: ["Auth"],
        summary: "Verify email OTP",
        description: "Confirms the OTP code sent to email and updates the user's email verification timestamp.",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["code"],
                properties: [
                    new OA\Property(property: "code", type: "string", example: "1234"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Email verified",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Email verified successfully."),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "email_verified_at", type: "string", format: "date-time", example: "2026-02-13T12:01:00Z"),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 422, description: "Invalid or expired code"),
        ]
    )]
    public static function verifyEmailOtp(): void {}
}
