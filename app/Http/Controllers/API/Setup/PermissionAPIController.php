<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Requests\API\Setup\CreatePermissionAPIRequest;
use App\Http\Requests\API\Setup\UpdatePermissionAPIRequest;
use App\Models\Setup\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

/**
 * Class PermissionAPIController
 */
class PermissionAPIController extends Controller
{
    /**
     * Display a listing of the Permissions.
     * GET|HEAD /permissions
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Permission();
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
        $permissions = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $permissions->items(),
            'meta' => [
                'current_page' => $permissions->currentPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
                'last_page' => $permissions->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Permission in storage.
     * POST /permissions
     */
    public function store(CreatePermissionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Permission $permission */
        $permission = Permission::create($input);

                return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permission saved successfully'
        ]);
            }

    /**
     * Display the specified Permission.
     * GET|HEAD /permissions/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Permission $permission */
        $permission = Permission::find($id);

        if (empty($permission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permission retrieved successfully'
        ]);
            }

    /**
     * Update the specified Permission in storage.
     * PUT/PATCH /permissions/{id}
     */
    public function update($id, UpdatePermissionAPIRequest $request): JsonResponse
    {
        /** @var Permission $permission */
        $permission = Permission::find($id);

        if (empty($permission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ],404);
                    }

        $permission->fill($request->all());
        $permission->save();

                return response()->json([
            'success' => true,
            'data' => $permission,
            'message' => 'Permission updated successfully'
        ]);
            }

    /**
     * Remove the specified Permission from storage.
     * DELETE /permissions/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Permission $permission */
        $permission = Permission::find($id);

        if (empty($permission)) {
                        return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ],404);
                    }

        $permission->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Permission deleted successfully'
        ]);
            }
}
