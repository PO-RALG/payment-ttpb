<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Requests\API\Setup\CreateRoleAPIRequest;
use App\Http\Requests\API\Setup\UpdateRoleAPIRequest;
use App\Models\Setup\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

/**
 * Class RoleAPIController
 */
class RoleAPIController extends Controller
{
    /**
     * Display a listing of the Roles.
     * GET|HEAD /roles
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Role();
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
        $roles = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $roles->items(),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
                'last_page' => $roles->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Role in storage.
     * POST /roles
     */
    public function store(CreateRoleAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Role $role */
        $role = Role::create($input);

                return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Role saved successfully'
        ]);
            }

    /**
     * Display the specified Role.
     * GET|HEAD /roles/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Role $role */
        $role = Role::find($id);

        if (empty($role)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Role retrieved successfully'
        ]);
            }

    /**
     * Update the specified Role in storage.
     * PUT/PATCH /roles/{id}
     */
    public function update($id, UpdateRoleAPIRequest $request): JsonResponse
    {
        /** @var Role $role */
        $role = Role::find($id);

        if (empty($role)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ],404);
                    }

        $role->fill($request->all());
        $role->save();

                return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Role updated successfully'
        ]);
            }

    /**
     * Remove the specified Role from storage.
     * DELETE /roles/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Role $role */
        $role = Role::find($id);

        if (empty($role)) {
                        return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ],404);
                    }

        $role->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Role deleted successfully'
        ]);
            }
}
