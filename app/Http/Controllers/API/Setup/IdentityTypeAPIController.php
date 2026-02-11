<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateIdentityTypeAPIRequest;
use App\Http\Requests\API\Setup\UpdateIdentityTypeAPIRequest;
use App\Models\Setup\IdentityType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class IdentityTypeAPIController
 */
class IdentityTypeAPIController extends Controller
{
    /**
     * Display a listing of the IdentityTypes.
     * GET|HEAD /identity-types
     */
    public function index(Request $request): JsonResponse
    {
        $model = new IdentityType();
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
        $identityTypes = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $identityTypes->items(),
            'meta' => [
                'current_page' => $identityTypes->currentPage(),
                'per_page' => $identityTypes->perPage(),
                'total' => $identityTypes->total(),
                'last_page' => $identityTypes->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created IdentityType in storage.
     * POST /identity-types
     */
    public function store(CreateIdentityTypeAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var IdentityType $identityType */
        $identityType = IdentityType::create($input);

                return response()->json([
            'success' => true,
            'data' => $identityType,
            'message' => 'Identity Type saved successfully'
        ]);
            }

    /**
     * Display the specified IdentityType.
     * GET|HEAD /identity-types/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var IdentityType $identityType */
        $identityType = IdentityType::find($id);

        if (empty($identityType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Identity Type not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $identityType,
            'message' => 'Identity Type retrieved successfully'
        ]);
            }

    /**
     * Update the specified IdentityType in storage.
     * PUT/PATCH /identity-types/{id}
     */
    public function update($id, UpdateIdentityTypeAPIRequest $request): JsonResponse
    {
        /** @var IdentityType $identityType */
        $identityType = IdentityType::find($id);

        if (empty($identityType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Identity Type not found'
            ],404);
                    }

        $identityType->fill($request->all());
        $identityType->save();

                return response()->json([
            'success' => true,
            'data' => $identityType,
            'message' => 'IdentityType updated successfully'
        ]);
            }

    /**
     * Remove the specified IdentityType from storage.
     * DELETE /identity-types/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var IdentityType $identityType */
        $identityType = IdentityType::find($id);

        if (empty($identityType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Identity Type not found'
            ],404);
                    }

        $identityType->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Identity Type deleted successfully'
        ]);
            }
}
