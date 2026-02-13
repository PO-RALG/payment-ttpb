<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setup\AdminArea;
use App\Models\Setup\AdminAreaLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAreaAPIController extends Controller
{
    public function getWards(): JsonResponse
    {
        $wards = AdminArea::query()
            ->with([
                'level:id,name,name_sw,order_id',
                'parent:id,name,parent_area_id,area_type_id',
                'parent.parent:id,name,parent_area_id,area_type_id',
            ])
            ->where('area_type_id', AdminAreaLevel::LEVEL_WARD)
            ->select('id', 'name', 'parent_area_id', 'area_type_id')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function (AdminArea $ward) {
                return [
                    'id' => $ward->id,
                    'name' => $ward->name,
                    'display_name' => $this->composeDisplayName($ward),
                    'parent_area_id' => $ward->parent_area_id,
                    'area_type_id' => $ward->area_type_id,
                    'level' => $this->formatLevel($ward->level),
                ];
            });

        return response()->json([
            'message' => 'Wards with hierarchical names retrieved successfully.',
            'data' => $wards,
        ]);
    }

    public function getByLevel(int $levelId): JsonResponse
    {
        $adminAreas = AdminArea::query()
            ->with(['level:id,name,name_sw,order_id'])
            ->where('area_type_id', $levelId)
            ->select('id', 'name', 'parent_area_id', 'area_type_id')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function (AdminArea $area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'parent_area_id' => $area->parent_area_id,
                    'area_type_id' => $area->area_type_id,
                    'level' => $this->formatLevel($area->level),
                ];
            });

        return response()->json([
            'message' => 'Admin areas by level retrieved successfully.',
            'data' => $adminAreas,
        ]);
    }

    public function withChildren(Request $request, ?int $id = null): JsonResponse
    {
        $adminAreaId = $id ?? $request->user()?->admin_area_id;

        if (! $adminAreaId) {
            return response()->json([
                'message' => 'An admin area id is required.',
                'data' => null,
            ], 422);
        }

        $adminArea = AdminArea::query()
            ->with([
                'level:id,name,name_sw,order_id',
                'children:id,name,parent_area_id,area_type_id',
                'children.level:id,name,name_sw,order_id',
            ])
            ->select('id', 'name', 'parent_area_id', 'area_type_id')
            ->find($adminAreaId);

        if (! $adminArea) {
            return response()->json([
                'message' => 'Admin area not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Admin area retrieved successfully.',
            'data' => $adminArea->toArray(),
        ]);
    }

    private function composeDisplayName(AdminArea $ward): string
    {
        $segments = [$ward->name];

        if ($ward->parent) {
            $segments[] = $ward->parent->name;

            if ($ward->parent->parent) {
                $segments[] = $ward->parent->parent->name;
            }
        }

        return implode(' - ', $segments);
    }

    private function formatLevel(?AdminAreaLevel $level): ?array
    {
        if (! $level) {
            return null;
        }

        return [
            'id' => $level->id,
            'name' => $level->name,
            'name_sw' => $level->name_sw,
            'order_id' => $level->order_id,
        ];
    }
}
