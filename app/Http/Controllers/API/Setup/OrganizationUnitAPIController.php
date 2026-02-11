<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateOrganizationUnitAPIRequest;
use App\Http\Requests\API\Setup\UpdateOrganizationUnitAPIRequest;
use App\Models\Setup\OrganizationUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class OrganizationUnitAPIController
 */
class OrganizationUnitAPIController extends Controller
{
    /**
     * Display a listing of the OrganizationUnits.
     * GET|HEAD /organization-units
     */
    public function index(Request $request): JsonResponse
    {
        $model = new OrganizationUnit();
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
        $organizationUnits = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $organizationUnits->items(),
            'meta' => [
                'current_page' => $organizationUnits->currentPage(),
                'per_page' => $organizationUnits->perPage(),
                'total' => $organizationUnits->total(),
                'last_page' => $organizationUnits->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created OrganizationUnit in storage.
     * POST /organization-units
     */
    public function store(CreateOrganizationUnitAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var OrganizationUnit $organizationUnit */
        $organizationUnit = OrganizationUnit::create($input);

                return response()->json([
            'success' => true,
            'data' => $organizationUnit,
            'message' => 'Organization Unit saved successfully'
        ]);
            }

    /**
     * Display the specified OrganizationUnit.
     * GET|HEAD /organization-units/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var OrganizationUnit $organizationUnit */
        $organizationUnit = OrganizationUnit::find($id);

        if (empty($organizationUnit)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Unit not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $organizationUnit,
            'message' => 'Organization Unit retrieved successfully'
        ]);
            }

    /**
     * Update the specified OrganizationUnit in storage.
     * PUT/PATCH /organization-units/{id}
     */
    public function update($id, UpdateOrganizationUnitAPIRequest $request): JsonResponse
    {
        /** @var OrganizationUnit $organizationUnit */
        $organizationUnit = OrganizationUnit::find($id);

        if (empty($organizationUnit)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Unit not found'
            ],404);
                    }

        $organizationUnit->fill($request->all());
        $organizationUnit->save();

                return response()->json([
            'success' => true,
            'data' => $organizationUnit,
            'message' => 'OrganizationUnit updated successfully'
        ]);
            }

    /**
     * Remove the specified OrganizationUnit from storage.
     * DELETE /organization-units/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var OrganizationUnit $organizationUnit */
        $organizationUnit = OrganizationUnit::find($id);

        if (empty($organizationUnit)) {
                        return response()->json([
                'success' => false,
                'message' => 'Organization Unit not found'
            ],404);
                    }

        $organizationUnit->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Organization Unit deleted successfully'
        ]);
            }
}
