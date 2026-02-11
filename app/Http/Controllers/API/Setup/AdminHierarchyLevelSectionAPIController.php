<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateAdminHierarchyLevelSectionAPIRequest;
use App\Http\Requests\API\Setup\UpdateAdminHierarchyLevelSectionAPIRequest;
use App\Models\Setup\AdminHierarchyLevelSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdminHierarchyLevelSectionAPIController
 */
class AdminHierarchyLevelSectionAPIController extends Controller
{
    /**
     * Display a listing of the AdminHierarchyLevelSections.
     * GET|HEAD /admin-hierarchy-level-sections
     */
    public function index(Request $request): JsonResponse
    {
        $model = new AdminHierarchyLevelSection();
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
        $adminHierarchyLevelSections = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevelSections->items(),
            'meta' => [
                'current_page' => $adminHierarchyLevelSections->currentPage(),
                'per_page' => $adminHierarchyLevelSections->perPage(),
                'total' => $adminHierarchyLevelSections->total(),
                'last_page' => $adminHierarchyLevelSections->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created AdminHierarchyLevelSection in storage.
     * POST /admin-hierarchy-level-sections
     */
    public function store(CreateAdminHierarchyLevelSectionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var AdminHierarchyLevelSection $adminHierarchyLevelSection */
        $adminHierarchyLevelSection = AdminHierarchyLevelSection::create($input);

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevelSection,
            'message' => 'Admin Hierarchy Level Section saved successfully'
        ]);
            }

    /**
     * Display the specified AdminHierarchyLevelSection.
     * GET|HEAD /admin-hierarchy-level-sections/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var AdminHierarchyLevelSection $adminHierarchyLevelSection */
        $adminHierarchyLevelSection = AdminHierarchyLevelSection::find($id);

        if (empty($adminHierarchyLevelSection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level Section not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevelSection,
            'message' => 'Admin Hierarchy Level Section retrieved successfully'
        ]);
            }

    /**
     * Update the specified AdminHierarchyLevelSection in storage.
     * PUT/PATCH /admin-hierarchy-level-sections/{id}
     */
    public function update($id, UpdateAdminHierarchyLevelSectionAPIRequest $request): JsonResponse
    {
        /** @var AdminHierarchyLevelSection $adminHierarchyLevelSection */
        $adminHierarchyLevelSection = AdminHierarchyLevelSection::find($id);

        if (empty($adminHierarchyLevelSection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level Section not found'
            ],404);
                    }

        $adminHierarchyLevelSection->fill($request->all());
        $adminHierarchyLevelSection->save();

                return response()->json([
            'success' => true,
            'data' => $adminHierarchyLevelSection,
            'message' => 'AdminHierarchyLevelSection updated successfully'
        ]);
            }

    /**
     * Remove the specified AdminHierarchyLevelSection from storage.
     * DELETE /admin-hierarchy-level-sections/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var AdminHierarchyLevelSection $adminHierarchyLevelSection */
        $adminHierarchyLevelSection = AdminHierarchyLevelSection::find($id);

        if (empty($adminHierarchyLevelSection)) {
                        return response()->json([
                'success' => false,
                'message' => 'Admin Hierarchy Level Section not found'
            ],404);
                    }

        $adminHierarchyLevelSection->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Admin Hierarchy Level Section deleted successfully'
        ]);
            }
}
