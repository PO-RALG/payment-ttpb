<?php

namespace App\Swagger\Endpoints;

use OpenApi\Attributes as OA;

class AdminAreaEndpoints
{
    #[OA\Get(
        path: "/api/admin_areas/wards",
        tags: ["Admin Areas"],
        summary: "List wards with hierarchical labels",
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Wards retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Wards with hierarchical names retrieved successfully."),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/AdminAreaResource")
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
        ]
    )]
    public static function wards(): void {}

    #[OA\Get(
        path: "/api/admin_areas/by_level/{levelId}",
        tags: ["Admin Areas"],
        summary: "List areas filtered by level",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "levelId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "Admin area level identifier (e.g., 4 for wards)"
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Admin areas retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Admin areas by level retrieved successfully."),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/AdminAreaResource")
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
        ]
    )]
    public static function byLevel(): void {}

    #[OA\Get(
        path: "/api/admin_areas_children/{id}",
        tags: ["Admin Areas"],
        summary: "Retrieve an admin area with its immediate children",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: false,
                schema: new OA\Schema(type: "integer"),
                description: "Admin area id; if omitted, the authenticated user's default area is used"
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Admin area retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Admin area retrieved successfully."),
                        new OA\Property(property: "data", ref: "#/components/schemas/AdminAreaResource"),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Not found"),
        ]
    )]
    public static function withChildren(): void {}
}
