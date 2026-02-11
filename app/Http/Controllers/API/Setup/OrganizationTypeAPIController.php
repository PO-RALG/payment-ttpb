<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateOrganizationTypeAPIRequest;
use App\Http\Requests\API\Setup\UpdateOrganizationTypeAPIRequest;
use App\Models\Setup\OrganizationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class OrganizationTypeAPIController
 */
class OrganizationTypeAPIController extends Controller
{
    /**
     * Display a listing of the OrganizationTypes.
     * GET|HEAD /organization-types
     */
    public function index(Request $request): JsonResponse
    {
        $model = new OrganizationType();
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
        $organizationTypes = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $organizationTypes->items(),
            'meta' => [
                'current_page' => $organizationTypes->currentPage(),
                'per_page' => $organizationTypes->perPage(),
                'total' => $organizationTypes->total(),
                'last_page' => $organizationTypes->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created OrganizationType in storage.
     * POST /organization-types
     */
    public function store(CreateOrganizationTypeAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var OrganizationType $organizationType */
        $organizationType = OrganizationType::create($input);

                return response()->json([
            'success' => true,
            'data' => $organizationType,
            'message' => 'Organization Type saved successfully'
        ]);
            }

    /**
     * Display the specified OrganizationType.
     * GET|HEAD /organization-types/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var OrganizationType $organizationType */
        $organizationType = OrganizationType::find($id);

        if (empty($organizationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Type not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $organizationType,
            'message' => 'Organization Type retrieved successfully'
        ]);
            }

    /**
     * Update the specified OrganizationType in storage.
     * PUT/PATCH /organization-types/{id}
     */
    public function update($id, UpdateOrganizationTypeAPIRequest $request): JsonResponse
    {
        /** @var OrganizationType $organizationType */
        $organizationType = OrganizationType::find($id);

        if (empty($organizationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Type not found'
            ],404);
                    }

        $organizationType->fill($request->all());
        $organizationType->save();

                return response()->json([
            'success' => true,
            'data' => $organizationType,
            'message' => 'OrganizationType updated successfully'
        ]);
            }

    /**
     * Remove the specified OrganizationType from storage.
     * DELETE /organization-types/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var OrganizationType $organizationType */
        $organizationType = OrganizationType::find($id);

        if (empty($organizationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Type not found'
            ],404);
                    }

        $organizationType->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Organization Type deleted successfully'
        ]);
            }
}
