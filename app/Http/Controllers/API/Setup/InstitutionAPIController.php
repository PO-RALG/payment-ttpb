<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateInstitutionAPIRequest;
use App\Http\Requests\API\Setup\UpdateInstitutionAPIRequest;
use App\Models\Setup\Institution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class InstitutionAPIController
 */
class InstitutionAPIController extends Controller
{
    /**
     * Display a listing of the Institutions.
     * GET|HEAD /institutions
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Institution();
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
        $institutions = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $institutions->items(),
            'meta' => [
                'current_page' => $institutions->currentPage(),
                'per_page' => $institutions->perPage(),
                'total' => $institutions->total(),
                'last_page' => $institutions->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Institution in storage.
     * POST /institutions
     */
    public function store(CreateInstitutionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Institution $institution */
        $institution = Institution::create($input);

                return response()->json([
            'success' => true,
            'data' => $institution,
            'message' => 'Institution saved successfully'
        ]);
            }

    /**
     * Display the specified Institution.
     * GET|HEAD /institutions/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Institution $institution */
        $institution = Institution::find($id);

        if (empty($institution)) {
                        return response()->json([
                'success' => false,
                'message' => 'Institution not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $institution,
            'message' => 'Institution retrieved successfully'
        ]);
            }

    /**
     * Update the specified Institution in storage.
     * PUT/PATCH /institutions/{id}
     */
    public function update($id, UpdateInstitutionAPIRequest $request): JsonResponse
    {
        /** @var Institution $institution */
        $institution = Institution::find($id);

        if (empty($institution)) {
                        return response()->json([
                'success' => false,
                'message' => 'Institution not found'
            ],404);
                    }

        $institution->fill($request->all());
        $institution->save();

                return response()->json([
            'success' => true,
            'data' => $institution,
            'message' => 'Institution updated successfully'
        ]);
            }

    /**
     * Remove the specified Institution from storage.
     * DELETE /institutions/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Institution $institution */
        $institution = Institution::find($id);

        if (empty($institution)) {
                        return response()->json([
                'success' => false,
                'message' => 'Institution not found'
            ],404);
                    }

        $institution->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Institution deleted successfully'
        ]);
            }
}
