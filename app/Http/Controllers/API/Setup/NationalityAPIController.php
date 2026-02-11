<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Requests\API\Setup\CreateNationalityAPIRequest;
use App\Http\Requests\API\Setup\UpdateNationalityAPIRequest;
use App\Models\Setup\Nationality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;

/**
 * Class NationalityAPIController
 */
class NationalityAPIController extends Controller
{
    /**
     * Display a listing of the Nationalities.
     * GET|HEAD /nationalities
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Nationality();
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
        $nationalities = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $nationalities->items(),
            'meta' => [
                'current_page' => $nationalities->currentPage(),
                'per_page' => $nationalities->perPage(),
                'total' => $nationalities->total(),
                'last_page' => $nationalities->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Nationality in storage.
     * POST /nationalities
     */
    public function store(CreateNationalityAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Nationality $nationality */
        $nationality = Nationality::create($input);

                return response()->json([
            'success' => true,
            'data' => $nationality,
            'message' => 'Nationality saved successfully'
        ]);
            }

    /**
     * Display the specified Nationality.
     * GET|HEAD /nationalities/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Nationality $nationality */
        $nationality = Nationality::find($id);

        if (empty($nationality)) {
                        return response()->json([
                'success' => false,
                'message' => 'Nationality not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $nationality,
            'message' => 'Nationality retrieved successfully'
        ]);
            }

    /**
     * Update the specified Nationality in storage.
     * PUT/PATCH /nationalities/{id}
     */
    public function update($id, UpdateNationalityAPIRequest $request): JsonResponse
    {
        /** @var Nationality $nationality */
        $nationality = Nationality::find($id);

        if (empty($nationality)) {
                        return response()->json([
                'success' => false,
                'message' => 'Nationality not found'
            ],404);
                    }

        $nationality->fill($request->all());
        $nationality->save();

                return response()->json([
            'success' => true,
            'data' => $nationality,
            'message' => 'Nationality updated successfully'
        ]);
            }

    /**
     * Remove the specified Nationality from storage.
     * DELETE /nationalities/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Nationality $nationality */
        $nationality = Nationality::find($id);

        if (empty($nationality)) {
                        return response()->json([
                'success' => false,
                'message' => 'Nationality not found'
            ],404);
                    }

        $nationality->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Nationality deleted successfully'
        ]);
            }
}
