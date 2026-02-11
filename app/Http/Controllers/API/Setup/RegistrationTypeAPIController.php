<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateRegistrationTypeAPIRequest;
use App\Http\Requests\API\Setup\UpdateRegistrationTypeAPIRequest;
use App\Models\Setup\RegistrationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class RegistrationTypeAPIController
 */
class RegistrationTypeAPIController extends Controller
{
    /**
     * Display a listing of the RegistrationTypes.
     * GET|HEAD /registration-types
     */
    public function index(Request $request): JsonResponse
    {
        $model = new RegistrationType();
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
        $registrationTypes = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $registrationTypes->items(),
            'meta' => [
                'current_page' => $registrationTypes->currentPage(),
                'per_page' => $registrationTypes->perPage(),
                'total' => $registrationTypes->total(),
                'last_page' => $registrationTypes->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created RegistrationType in storage.
     * POST /registration-types
     */
    public function store(CreateRegistrationTypeAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var RegistrationType $registrationType */
        $registrationType = RegistrationType::create($input);

                return response()->json([
            'success' => true,
            'data' => $registrationType,
            'message' => 'Registration Type saved successfully'
        ]);
            }

    /**
     * Display the specified RegistrationType.
     * GET|HEAD /registration-types/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var RegistrationType $registrationType */
        $registrationType = RegistrationType::find($id);

        if (empty($registrationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Registration Type not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $registrationType,
            'message' => 'Registration Type retrieved successfully'
        ]);
            }

    /**
     * Update the specified RegistrationType in storage.
     * PUT/PATCH /registration-types/{id}
     */
    public function update($id, UpdateRegistrationTypeAPIRequest $request): JsonResponse
    {
        /** @var RegistrationType $registrationType */
        $registrationType = RegistrationType::find($id);

        if (empty($registrationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Registration Type not found'
            ],404);
                    }

        $registrationType->fill($request->all());
        $registrationType->save();

                return response()->json([
            'success' => true,
            'data' => $registrationType,
            'message' => 'RegistrationType updated successfully'
        ]);
            }

    /**
     * Remove the specified RegistrationType from storage.
     * DELETE /registration-types/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var RegistrationType $registrationType */
        $registrationType = RegistrationType::find($id);

        if (empty($registrationType)) {
                        return response()->json([
                'success' => false,
                'message' => 'Registration Type not found'
            ],404);
                    }

        $registrationType->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Registration Type deleted successfully'
        ]);
            }
}
