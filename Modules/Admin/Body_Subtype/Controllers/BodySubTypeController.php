<?php

namespace Modules\Admin\Body_Subtype\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Exception;

class BodySubTypeController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|integer|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $bodysubtype = BodySubType::create($request->only(['name', 'status']));

            return response()->json([
                'message' => 'New Body Sub Type Added Successfully',
                'data' => $bodysubtype,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->page === '0' ? BodySubType::count() : $request->input('per_page', 10);
            $data = BodySubType::orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'pagination' => [
                    'total_count' => $data->total(),
                    'total_page' => $data->lastPage(),
                    'current_page' => $data->currentPage(),
                    'current_page_count' => $data->count(),
                    'next_page' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                    'previous_page' => $data->onFirstPage() ? null : $data->currentPage(),
                ],
                'message' => 'Data Retrieved Successfully',
                'data' => $data->items(),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $bodysubtype = BodySubType::find($id);

            if (!$bodysubtype) {
                return response()->json(['message' => 'Body Sub Type not found'], 404);
            }

            return response()->json([
                'message' => 'Body Sub Type data retrieved successfully',
                'data' => $bodysubtype,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|integer|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $bodysubtype = BodySubType::find($id);
            if (!$bodysubtype) {
                return response()->json(['message' => 'Body Sub Type Not Found'], 404);
            }

            $bodysubtype->update($request->only(['name', 'status']));

            return response()->json([
                'message' => 'Body Sub Type Updated Successfully',
                'data' => $bodysubtype,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $bodysubtype = BodySubType::find($id);
            if (!$bodysubtype) {
                return response()->json(['message' => 'Body Sub Type Not Found'], 404);
            }

            $bodysubtype->delete();

            return response()->json([
                'message' => 'Body Sub Type Deleted Successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}