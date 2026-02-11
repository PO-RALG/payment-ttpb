<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateAdminHierarchySectionAPIRequest;
use App\Http\Requests\API\Setup\UpdateAdminHierarchySectionAPIRequest;
use App\Models\Setup\AdminHierarchySection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdminHierarchySectionAPIController
 */
class AdminHierarchySectionAPIController extends Controller
{
    /**
     * Display a listing of the AdminHierarchySections.
     * GET|HEAD /admin-hierarchy-sections
     */
    public function index(Request $request): JsonResponse
    {
        $model = new AdminHierarchySection();
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
        $adminHierarchySections = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $adminHierarchySections->items(),
            'meta' => [
                'current_page' => $adminHierarchySections->currentPage(),
                'per_page' => $adminHierarchySections->perPage(),
                'total' => $adminHierarchySections->total(),
                'last_page' => $adminHierarchySections->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created AdminHierarchySection in storage.
     * POST /admin-hierarchy-sections
     */
    public function store(CreateAdminHierarchySectionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var AdminHierarchySection $adminHierarchySection */
        $adminHierarchySection = AdminHierarchySection::create($input);

                return response()->json([
            'success' => true,
            'data' => $adminHierarchySection,
            'message' => 'Admin Hierarchy Section saved successfully'
        ]);
            }

    /**
     * Display the specified AdminHierarchySection.
     * GET|HEAD /admin-hierarchy-sections/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var AdminHierarchySection $adminHierarchySection */
        $adminHierarchySection = AdminHierarchySection::find($id);

        if (empty($adminHierarchySection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Section not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $adminHierarchySection,
            'message' => 'Admin Hierarchy Section retrieved successfully'
        ]);
            }

    /**
     * Update the specified AdminHierarchySection in storage.
     * PUT/PATCH /admin-hierarchy-sections/{id}
     */
    public function update($id, UpdateAdminHierarchySectionAPIRequest $request): JsonResponse
    {
        /** @var AdminHierarchySection $adminHierarchySection */
        $adminHierarchySection = AdminHierarchySection::find($id);

        if (empty($adminHierarchySection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Section not found'
            ],404);
                    }

        $adminHierarchySection->fill($request->all());
        $adminHierarchySection->save();

                return response()->json([
            'success' => true,
            'data' => $adminHierarchySection,
            'message' => 'AdminHierarchySection updated successfully'
        ]);
            }

    /**
     * Remove the specified AdminHierarchySection from storage.
     * DELETE /admin-hierarchy-sections/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var AdminHierarchySection $adminHierarchySection */
        $adminHierarchySection = AdminHierarchySection::find($id);

        if (empty($adminHierarchySection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Section not found'
            ],404);
                    }

        $adminHierarchySection->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Admin Hierarchy Section deleted successfully'
        ]);
            }
}
