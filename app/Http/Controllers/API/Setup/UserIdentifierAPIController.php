<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateUserIdentifierAPIRequest;
use App\Http\Requests\API\Setup\UpdateUserIdentifierAPIRequest;
use App\Models\Setup\UserIdentifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserIdentifierAPIController
 */
class UserIdentifierAPIController extends Controller
{
    /**
     * Display a listing of the UserIdentifiers.
     * GET|HEAD /user-identifiers
     */
    public function index(Request $request): JsonResponse
    {
        $model = new UserIdentifier();
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
        $userIdentifiers = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $userIdentifiers->items(),
            'meta' => [
                'current_page' => $userIdentifiers->currentPage(),
                'per_page' => $userIdentifiers->perPage(),
                'total' => $userIdentifiers->total(),
                'last_page' => $userIdentifiers->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created UserIdentifier in storage.
     * POST /user-identifiers
     */
    public function store(CreateUserIdentifierAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var UserIdentifier $userIdentifier */
        $userIdentifier = UserIdentifier::create($input);

                return response()->json([
            'success' => true,
            'data' => $userIdentifier,
            'message' => 'User Identifier saved successfully'
        ]);
            }

    /**
     * Display the specified UserIdentifier.
     * GET|HEAD /user-identifiers/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var UserIdentifier $userIdentifier */
        $userIdentifier = UserIdentifier::find($id);

        if (empty($userIdentifier)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Identifier not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $userIdentifier,
            'message' => 'User Identifier retrieved successfully'
        ]);
            }

    /**
     * Update the specified UserIdentifier in storage.
     * PUT/PATCH /user-identifiers/{id}
     */
    public function update($id, UpdateUserIdentifierAPIRequest $request): JsonResponse
    {
        /** @var UserIdentifier $userIdentifier */
        $userIdentifier = UserIdentifier::find($id);

        if (empty($userIdentifier)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Identifier not found'
            ],404);
                    }

        $userIdentifier->fill($request->all());
        $userIdentifier->save();

                return response()->json([
            'success' => true,
            'data' => $userIdentifier,
            'message' => 'UserIdentifier updated successfully'
        ]);
            }

    /**
     * Remove the specified UserIdentifier from storage.
     * DELETE /user-identifiers/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var UserIdentifier $userIdentifier */
        $userIdentifier = UserIdentifier::find($id);

        if (empty($userIdentifier)) {
                        return response()->json([
                'success' => false,
                'message' => 'User Identifier not found'
            ],404);
                    }

        $userIdentifier->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'User Identifier deleted successfully'
        ]);
            }
}
