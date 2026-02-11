<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateDesignationAPIRequest;
use App\Http\Requests\API\Setup\UpdateDesignationAPIRequest;
use App\Models\Setup\Designation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class DesignationAPIController
 */
class DesignationAPIController extends Controller
{
    /**
     * Display a listing of the Designations.
     * GET|HEAD /designations
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Designation();
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
        $designations = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $designations->items(),
            'meta' => [
                'current_page' => $designations->currentPage(),
                'per_page' => $designations->perPage(),
                'total' => $designations->total(),
                'last_page' => $designations->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Designation in storage.
     * POST /designations
     */
    public function store(CreateDesignationAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Designation $designation */
        $designation = Designation::create($input);

                return response()->json([
            'success' => true,
            'data' => $designation,
            'message' => 'Designation saved successfully'
        ]);
            }

    /**
     * Display the specified Designation.
     * GET|HEAD /designations/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Designation $designation */
        $designation = Designation::find($id);

        if (empty($designation)) {
                        return response()->json([
                'success' => false,
                'message' => 'Designation not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $designation,
            'message' => 'Designation retrieved successfully'
        ]);
            }

    /**
     * Update the specified Designation in storage.
     * PUT/PATCH /designations/{id}
     */
    public function update($id, UpdateDesignationAPIRequest $request): JsonResponse
    {
        /** @var Designation $designation */
        $designation = Designation::find($id);

        if (empty($designation)) {
                        return response()->json([
                'success' => false,
                'message' => 'Designation not found'
            ],404);
                    }

        $designation->fill($request->all());
        $designation->save();

                return response()->json([
            'success' => true,
            'data' => $designation,
            'message' => 'Designation updated successfully'
        ]);
            }

    /**
     * Remove the specified Designation from storage.
     * DELETE /designations/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Designation $designation */
        $designation = Designation::find($id);

        if (empty($designation)) {
                        return response()->json([
                'success' => false,
                'message' => 'Designation not found'
            ],404);
                    }

        $designation->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Designation deleted successfully'
        ]);
            }
}
