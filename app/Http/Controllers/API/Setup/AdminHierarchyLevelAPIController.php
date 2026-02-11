<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateAdminHierarchyLevelAPIRequest;
use App\Http\Requests\API\Setup\UpdateAdminHierarchyLevelAPIRequest;
use App\Models\Setup\AdminHierarchyLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdminHierarchyLevelAPIController
 */
class AdminHierarchyLevelAPIController extends Controller
{
    /**
     * Display a listing of the AdminHierarchyLevels.
     * GET|HEAD /admin-hierarchy-levels
     */
    public function index(Request $request): JsonResponse
    {
        $model = new AdminHierarchyLevel();
        $query = $model->newQuery();
        $columns = Schema::getColumnListing($model->getTable());

        if ($search = $request->get('search')) {
            $searchableColumns = $columns;
            if ($request->get('search_columns')) {
                $requestedColumns = is_array($request->get('search_columns'))
                    ? $request->get('search_columns')
                    : explode(',', (string) $request->get('search_columns'));
                $searchableColumns = array_values(array_intersect($columns, $requestedColumns));
            }

            $query->where(function ($subQuery) use ($searchableColumns, $search) {
                foreach ($searchableColumns as $column) {
                    $subQuery->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }

        $filters = $request->get('filters', []);
        if (is_array($filters)) {
            foreach ($filters as $column => $value) {
                if (in_array($column, $columns, true) && $value !== null && $value !== '') {
                    $query->where($column, $value);
                }
            }
        }

        $perPage = (int) $request->get('per_page', 15);
        if ($perPage <= 0) {
            $perPage = 15;
        }
        $adminHierarchyLevels = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevels->items(),
            'meta' => [
                'current_page' => $adminHierarchyLevels->currentPage(),
                'per_page' => $adminHierarchyLevels->perPage(),
                'total' => $adminHierarchyLevels->total(),
                'last_page' => $adminHierarchyLevels->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created AdminHierarchyLevel in storage.
     * POST /admin-hierarchy-levels
     */
    public function store(CreateAdminHierarchyLevelAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var AdminHierarchyLevel $adminHierarchyLevel */
        $adminHierarchyLevel = AdminHierarchyLevel::create($input);

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevel,
            'message' => 'Admin Hierarchy Level saved successfully'
        ]);
            }

    /**
     * Display the specified AdminHierarchyLevel.
     * GET|HEAD /admin-hierarchy-levels/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var AdminHierarchyLevel $adminHierarchyLevel */
        $adminHierarchyLevel = AdminHierarchyLevel::find($id);

        if (empty($adminHierarchyLevel)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevel,
            'message' => 'Admin Hierarchy Level retrieved successfully'
        ]);
            }

    /**
     * Update the specified AdminHierarchyLevel in storage.
     * PUT/PATCH /admin-hierarchy-levels/{id}
     */
    public function update($id, UpdateAdminHierarchyLevelAPIRequest $request): JsonResponse
    {
        /** @var AdminHierarchyLevel $adminHierarchyLevel */
        $adminHierarchyLevel = AdminHierarchyLevel::find($id);

        if (empty($adminHierarchyLevel)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level not found'
            ],404);
                    }

        $adminHierarchyLevel->fill($request->all());
        $adminHierarchyLevel->save();

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevel,
            'message' => 'AdminHierarchyLevel updated successfully'
        ]);
            }

    /**
     * Remove the specified AdminHierarchyLevel from storage.
     * DELETE /admin-hierarchy-levels/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var AdminHierarchyLevel $adminHierarchyLevel */
        $adminHierarchyLevel = AdminHierarchyLevel::find($id);

        if (empty($adminHierarchyLevel)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level not found'
            ],404);
                    }

        $adminHierarchyLevel->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Admin Hierarchy Level deleted successfully'
        ]);
            }
}
