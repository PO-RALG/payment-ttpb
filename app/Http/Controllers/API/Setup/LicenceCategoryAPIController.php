<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateLicenceCategoryAPIRequest;
use App\Http\Requests\API\Setup\UpdateLicenceCategoryAPIRequest;
use App\Models\Setup\LicenceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class LicenceCategoryAPIController
 */
class LicenceCategoryAPIController extends Controller
{
    /**
     * Display a listing of the LicenceCategories.
     * GET|HEAD /licence-categories
     */
    public function index(Request $request): JsonResponse
    {
        $model = new LicenceCategory();
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
        $licenceCategories = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $licenceCategories->items(),
            'meta' => [
                'current_page' => $licenceCategories->currentPage(),
                'per_page' => $licenceCategories->perPage(),
                'total' => $licenceCategories->total(),
                'last_page' => $licenceCategories->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created LicenceCategory in storage.
     * POST /licence-categories
     */
    public function store(CreateLicenceCategoryAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var LicenceCategory $licenceCategory */
        $licenceCategory = LicenceCategory::create($input);

                return response()->json([
            'success' => true,
            'data' => $licenceCategory,
            'message' => 'Licence Category saved successfully'
        ]);
            }

    /**
     * Display the specified LicenceCategory.
     * GET|HEAD /licence-categories/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var LicenceCategory $licenceCategory */
        $licenceCategory = LicenceCategory::find($id);

        if (empty($licenceCategory)) {
                        return response()->json([
                'success' => false,
                'message' => 'Licence Category not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $licenceCategory,
            'message' => 'Licence Category retrieved successfully'
        ]);
            }

    /**
     * Update the specified LicenceCategory in storage.
     * PUT/PATCH /licence-categories/{id}
     */
    public function update($id, UpdateLicenceCategoryAPIRequest $request): JsonResponse
    {
        /** @var LicenceCategory $licenceCategory */
        $licenceCategory = LicenceCategory::find($id);

        if (empty($licenceCategory)) {
                        return response()->json([
                'success' => false,
                'message' => 'Licence Category not found'
            ],404);
                    }

        $licenceCategory->fill($request->all());
        $licenceCategory->save();

                return response()->json([
            'success' => true,
            'data' => $licenceCategory,
            'message' => 'LicenceCategory updated successfully'
        ]);
            }

    /**
     * Remove the specified LicenceCategory from storage.
     * DELETE /licence-categories/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var LicenceCategory $licenceCategory */
        $licenceCategory = LicenceCategory::find($id);

        if (empty($licenceCategory)) {
                        return response()->json([
                'success' => false,
                'message' => 'Licence Category not found'
            ],404);
                    }

        $licenceCategory->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Licence Category deleted successfully'
        ]);
            }
}
