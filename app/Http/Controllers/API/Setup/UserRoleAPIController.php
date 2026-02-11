<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateUserRoleAPIRequest;
use App\Http\Requests\API\Setup\UpdateUserRoleAPIRequest;
use App\Models\Setup\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserRoleAPIController
 */
class UserRoleAPIController extends Controller
{
    /**
     * Display a listing of the UserRoles.
     * GET|HEAD /user-roles
     */
    public function index(Request $request): JsonResponse
    {
        $model = new UserRole();
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
        $userRoles = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $userRoles->items(),
            'meta' => [
                'current_page' => $userRoles->currentPage(),
                'per_page' => $userRoles->perPage(),
                'total' => $userRoles->total(),
                'last_page' => $userRoles->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created UserRole in storage.
     * POST /user-roles
     */
    public function store(CreateUserRoleAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var UserRole $userRole */
        $userRole = UserRole::create($input);

                return response()->json([
            'success' => true,
            'data' => $userRole,
            'message' => 'User Role saved successfully'
        ]);
            }

    /**
     * Display the specified UserRole.
     * GET|HEAD /user-roles/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var UserRole $userRole */
        $userRole = UserRole::find($id);

        if (empty($userRole)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Role not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $userRole,
            'message' => 'User Role retrieved successfully'
        ]);
            }

    /**
     * Update the specified UserRole in storage.
     * PUT/PATCH /user-roles/{id}
     */
    public function update($id, UpdateUserRoleAPIRequest $request): JsonResponse
    {
        /** @var UserRole $userRole */
        $userRole = UserRole::find($id);

        if (empty($userRole)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Role not found'
            ],404);
                    }

        $userRole->fill($request->all());
        $userRole->save();

                return response()->json([
            'success' => true,
            'data' => $userRole,
            'message' => 'UserRole updated successfully'
        ]);
            }

    /**
     * Remove the specified UserRole from storage.
     * DELETE /user-roles/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var UserRole $userRole */
        $userRole = UserRole::find($id);

        if (empty($userRole)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Role not found'
            ],404);
                    }

        $userRole->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'User Role deleted successfully'
        ]);
            }
}
