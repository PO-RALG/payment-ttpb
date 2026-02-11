<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Requests\API\Setup\CreateRolePermissionAPIRequest;
use App\Http\Requests\API\Setup\UpdateRolePermissionAPIRequest;
use App\Models\Setup\RolePermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

/**
 * Class RolePermissionAPIController
 */
class RolePermissionAPIController extends Controller
{
    /**
     * Display a listing of the RolePermissions.
     * GET|HEAD /role-permissions
     */
    public function index(Request $request): JsonResponse
    {
        $model = new RolePermission();
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
        $rolePermissions = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $rolePermissions->items(),
            'meta' => [
                'current_page' => $rolePermissions->currentPage(),
                'per_page' => $rolePermissions->perPage(),
                'total' => $rolePermissions->total(),
                'last_page' => $rolePermissions->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created RolePermission in storage.
     * POST /role-permissions
     */
    public function store(CreateRolePermissionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var RolePermission $rolePermission */
        $rolePermission = RolePermission::create($input);

                return response()->json([
            'success' => true,
            'data' => $rolePermission,
            'message' => 'Role Permission saved successfully'
        ]);
            }

    /**
     * Display the specified RolePermission.
     * GET|HEAD /role-permissions/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var RolePermission $rolePermission */
        $rolePermission = RolePermission::find($id);

        if (empty($rolePermission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role Permission not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $rolePermission,
            'message' => 'Role Permission retrieved successfully'
        ]);
            }

    /**
     * Update the specified RolePermission in storage.
     * PUT/PATCH /role-permissions/{id}
     */
    public function update($id, UpdateRolePermissionAPIRequest $request): JsonResponse
    {
        /** @var RolePermission $rolePermission */
        $rolePermission = RolePermission::find($id);

        if (empty($rolePermission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role Permission not found'
            ],404);
                    }

        $rolePermission->fill($request->all());
        $rolePermission->save();

                return response()->json([
            'success' => true,
            'data' => $rolePermission,
            'message' => 'RolePermission updated successfully'
        ]);
            }

    /**
     * Remove the specified RolePermission from storage.
     * DELETE /role-permissions/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var RolePermission $rolePermission */
        $rolePermission = RolePermission::find($id);

        if (empty($rolePermission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role Permission not found'
            ],404);
                    }

        $rolePermission->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Role Permission deleted successfully'
        ]);
            }
}
