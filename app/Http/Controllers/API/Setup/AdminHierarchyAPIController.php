<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateAdminHierarchyAPIRequest;
use App\Http\Requests\API\UpdateAdminHierarchyAPIRequest;
use App\Models\Setup\AdminHierarchy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdminHierarchyAPIController
 */
class AdminHierarchyAPIController extends Controller
{
    /**
     * Display a listing of the AdminHierarchies.
     * GET|HEAD /admin-hierarchies
     */
    public function index(Request $request): JsonResponse
    {
        $model = new AdminHierarchy();
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
        $adminHierarchies = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $adminHierarchies->items(),
            'meta' => [
                'current_page' => $adminHierarchies->currentPage(),
                'per_page' => $adminHierarchies->perPage(),
                'total' => $adminHierarchies->total(),
                'last_page' => $adminHierarchies->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created AdminHierarchy in storage.
     * POST /admin-hierarchies
     */
    public function store(CreateAdminHierarchyAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var AdminHierarchy $adminHierarchy */
        $adminHierarchy = AdminHierarchy::create($input);

                return response()->json([
            'success' => true,
            'data' => $adminHierarchy,
            'message' => 'Admin Hierarchy saved successfully'
        ]);
            }

    /**
     * Display the specified AdminHierarchy.
     * GET|HEAD /admin-hierarchies/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var AdminHierarchy $adminHierarchy */
        $adminHierarchy = AdminHierarchy::find($id);

        if (empty($adminHierarchy)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $adminHierarchy,
            'message' => 'Admin Hierarchy retrieved successfully'
        ]);
            }

    /**
     * Update the specified AdminHierarchy in storage.
     * PUT/PATCH /admin-hierarchies/{id}
     */
    public function update($id, UpdateAdminHierarchyAPIRequest $request): JsonResponse
    {
        /** @var AdminHierarchy $adminHierarchy */
        $adminHierarchy = AdminHierarchy::find($id);

        if (empty($adminHierarchy)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy not found'
            ],404);
                    }

        $adminHierarchy->fill($request->all());
        $adminHierarchy->save();

                return response()->json([
            'success' => true,
            'data' => $adminHierarchy,
            'message' => 'AdminHierarchy updated successfully'
        ]);
            }

    /**
     * Remove the specified AdminHierarchy from storage.
     * DELETE /admin-hierarchies/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var AdminHierarchy $adminHierarchy */
        $adminHierarchy = AdminHierarchy::find($id);

        if (empty($adminHierarchy)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy not found'
            ],404);
                    }

        $adminHierarchy->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Admin Hierarchy deleted successfully'
        ]);
            }
}
