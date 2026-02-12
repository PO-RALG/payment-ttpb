<?php

namespace App\Swagger\Endpoints;

use OpenApi\Attributes as OA;

class NationalityEndpoints
{
    #[OA\Get(
        path: "/api/nationalities",
        tags: ["Nationalities"],
        summary: "List nationalities",
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "List retrieved", content: new OA\JsonContent(ref: "#/components/schemas/PaginatedResponse")),
        ]
    )]
    public static function index(): void {}

    #[OA\Post(
        path: "/api/nationalities",
        tags: ["Nationalities"],
        summary: "Create nationality",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(type: "object")
        ),
        responses: [
            new OA\Response(response: 201, description: "Created", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function store(): void {}

    #[OA\Get(
        path: "/api/nationalities/{nationality}",
        tags: ["Nationalities"],
        summary: "Retrieve nationality",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "nationality", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Found", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 404, description: "Not found"),
        ]
    )]
    public static function show(): void {}

    #[OA\Put(
        path: "/api/nationalities/{nationality}",
        tags: ["Nationalities"],
        summary: "Update nationality",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "nationality", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(type: "object")),
        responses: [
            new OA\Response(response: 200, description: "Updated", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
        ]
    )]
    public static function update(): void {}

    #[OA\Delete(
        path: "/api/nationalities/{nationality}",
        tags: ["Nationalities"],
        summary: "Delete nationality",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "nationality", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Deleted", content: new OA\JsonContent(ref: "#/components/schemas/StandardResponse")),
            new OA\Response(response: 404, description: "Not found"),
        ]
    )]
    public static function destroy(): void {}
}
