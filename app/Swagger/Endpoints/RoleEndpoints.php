<?php

namespace App\Swagger\Endpoints;

use OpenApi\Attributes as OA;

class RoleEndpoints
{
    #[OA\Get(
        path: "/api/setup/roles",
        tags: ["Roles"],
        summary: "List roles",
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "List of roles", content: new OA\JsonContent(ref: "#/components/schemas/PaginatedResponse")),
        ]
    )]
    public static function index(): void {}

    #[OA\Post(
        path: "/api/setup/roles",
        tags: ["Roles"],
        summary: "Create role",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(type: "object")),
        responses: [
            new OA\Response(response: 201, description: "Role created", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function store(): void {}

    #[OA\Get(
        path: "/api/setup/roles/{role}",
        tags: ["Roles"],
        summary: "Show role",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "role", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Role found", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 404, description: "Not found"),
        ]
    )]
    public static function show(): void {}

    #[OA\Put(
        path: "/api/setup/roles/{role}",
        tags: ["Roles"],
        summary: "Update role",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "role", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(type: "object")),
        responses: [
            new OA\Response(response: 200, description: "Role updated", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function update(): void {}

    #[OA\Delete(
        path: "/api/setup/roles/{role}",
        tags: ["Roles"],
        summary: "Delete role",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "role", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Role deleted", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function destroy(): void {}

    #[OA\Post(
        path: "/api/setup/roles/assign-permissions",
        tags: ["Roles"],
        summary: "Assign permissions to a role",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["role_id", "permissions"],
                properties: [
                    new OA\Property(property: "role_id", type: "integer"),
                    new OA\Property(property: "permissions", type: "array", items: new OA\Items(type: "integer")),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Permissions assigned", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function assignPermissions(): void {}
}
