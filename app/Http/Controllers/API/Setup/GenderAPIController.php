<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateGenderAPIRequest;
use App\Http\Requests\API\UpdateGenderAPIRequest;
use App\Models\Setup\Gender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class GenderAPIController
 */
class GenderAPIController extends Controller
{
    /**
     * Display a listing of the Genders.
     * GET|HEAD /genders
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Gender();
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
        $genders = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $genders->items(),
            'meta' => [
                'current_page' => $genders->currentPage(),
                'per_page' => $genders->perPage(),
                'total' => $genders->total(),
                'last_page' => $genders->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Gender in storage.
     * POST /genders
     */
    public function store(CreateGenderAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Gender $gender */
        $gender = Gender::create($input);

                return response()->json([
            'success' => true,
            'data' => $gender,
            'message' => 'Gender saved successfully'
        ]);
            }

    /**
     * Display the specified Gender.
     * GET|HEAD /genders/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Gender $gender */
        $gender = Gender::find($id);

        if (empty($gender)) {
                        return response()->json([
                'success' => false,
                'message' => 'Gender not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $gender,
            'message' => 'Gender retrieved successfully'
        ]);
            }

    /**
     * Update the specified Gender in storage.
     * PUT/PATCH /genders/{id}
     */
    public function update($id, UpdateGenderAPIRequest $request): JsonResponse
    {
        /** @var Gender $gender */
        $gender = Gender::find($id);

        if (empty($gender)) {
                        return response()->json([
                'success' => false,
                'message' => 'Gender not found'
            ],404);
                    }

        $gender->fill($request->all());
        $gender->save();

                return response()->json([
            'success' => true,
            'data' => $gender,
            'message' => 'Gender updated successfully'
        ]);
            }

    /**
     * Remove the specified Gender from storage.
     * DELETE /genders/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Gender $gender */
        $gender = Gender::find($id);

        if (empty($gender)) {
                        return response()->json([
                'success' => false,
                'message' => 'Gender not found'
            ],404);
                    }

        $gender->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Gender deleted successfully'
        ]);
            }
}
