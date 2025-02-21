<?php

namespace Modules\Admin\Body_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Type\Models\Body_Type;
use Exception;

class Body_TypeController extends Controller
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

            $body_type = Body_Type::create($request->only(['name', 'status']));

            return response()->json([
                'message' => 'New Body Type Added Successfully',
                'data' => $body_type,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $perPage = ($request->page === '0') ? Body_Type::count() : $request->input('per_page', 10);
            $data = Body_Type::orderBy('created_at', 'desc')->paginate($perPage);

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
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $body_type = Body_Type::find($id);

            if (!$body_type) {
                return response()->json(['message' => 'Body Type Not Found'], 404);
            }

            return response()->json([
                'message' => 'Body Type Data Retrieved Successfully',
                'data' => $body_type,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
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

            $body_type = Body_Type::find($id);

            if (!$body_type) {
                return response()->json(['message' => 'Body Type Not Found'], 404);
            }

            $body_type->update($request->only(['name', 'status']));

            return response()->json([
                'message' => 'Body Type Updated Successfully',
                'data' => $body_type,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $body_type = Body_Type::find($id);

            if (!$body_type) {
                return response()->json(['message' => 'Body Type Not Found'], 404);
            }

            $body_type->delete();

            return response()->json([
                'message' => 'Body Type Deleted Successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function pagination(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $data = Body_Type::paginate($perPage);

            return response()->json([
                'message' => 'Body Type Data Retrieved Successfully',
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
