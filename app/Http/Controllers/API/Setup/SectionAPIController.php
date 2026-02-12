<?php

namespace App\Http\Controllers\API\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Setup\CreateSectionAPIRequest;
use App\Http\Requests\API\Setup\UpdateSectionAPIRequest;
use App\Models\Setup\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class SectionAPIController
 */
class SectionAPIController extends Controller
{
    /**
     * Display a listing of the Sections.
     * GET|HEAD /sections
     */
    public function index(Request $request): JsonResponse
    {
        $model = new Section();
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
        $sections = $query->paginate($perPage)->appends($request->query());
                return response()->json([
            'success' => true,
            'data' => $sections->items(),
            'meta' => [
                'current_page' => $sections->currentPage(),
                'per_page' => $sections->perPage(),
                'total' => $sections->total(),
                'last_page' => $sections->lastPage(),
            ],
            'message' => 'Testes retrieved successfully'
        ]);
            }

    /**
     * Store a newly created Section in storage.
     * POST /sections
     */
    public function store(CreateSectionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Section $section */
        $section = Section::create($input);

                return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'Section saved successfully'
        ]);
            }

    /**
     * Display the specified Section.
     * GET|HEAD /sections/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Section $section */
        $section = Section::find($id);

        if (empty($section)) {
                        return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ],404);
                    }

                return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'Section retrieved successfully'
        ]);
            }

    /**
     * Update the specified Section in storage.
     * PUT/PATCH /sections/{id}
     */
    public function update($id, UpdateSectionAPIRequest $request): JsonResponse
    {
        /** @var Section $section */
        $section = Section::find($id);

        if (empty($section)) {
                        return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ],404);
                    }

        $section->fill($request->all());
        $section->save();

                return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'Section updated successfully'
        ]);
            }

    /**
     * Remove the specified Section from storage.
     * DELETE /sections/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Section $section */
        $section = Section::find($id);

        if (empty($section)) {
                        return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ],404);
                    }

        $section->delete();

                return response()->json([
            'success' => true,
            'data' => $id,
            'message' => 'Section deleted successfully'
        ]);
            }
}
