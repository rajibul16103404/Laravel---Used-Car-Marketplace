<?php

namespace Modules\Admin\Color\InteriorColor\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;

class InteriorColorController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $interior_color = InteriorColor::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
    
            return response()->json([
                'message' => 'New Interior Color Added Successfully',
                'data' => $interior_color,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the interior color.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function index(Request $request)
    {
        try {
            // Check if page parameter is '0' or set pagination limit
            if($request->page === '0'){
                $perPage = InteriorColor::count();
            }
            else{
                $perPage = $request->input('per_page', 10);
            }
    
            // Fetch paginated data
            $data = InteriorColor::orderBy('created_at', 'desc')->paginate($perPage);
    
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
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function show($id)
    {
        try {
            // Find product by ID
            $interior_color = InteriorColor::find($id);
    
            // Check if product exists
            if (!$interior_color) {
                return response()->json([
                    'message' => 'Interior Color not found',
                ], 404);
            }
    
            return response()->json([
                'message' => 'Interior Color data retrieved successfully',
                'data' => $interior_color,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'message' => 'An error occurred while fetching the Interior Color data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Find the Interior color record
        $interior_color = InteriorColor::find($id);
    
        if (!$interior_color) {
            return response()->json(['message' => 'Interior Color Not Found'], 404);
        }
    
        try {
            // Attempt to update the record
            $interior_color->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            // Catch any error during the update process and return an error message
            return response()->json([
                'message' => 'Failed to update Interior Color',
                'error' => $e->getMessage()
            ], 500);
        }
    
        // Return success response
        return response()->json([
            'message' => 'Interior Color Updated Successfully',
            'data' => $interior_color,
        ], 200);
    }
    


    public function destroy($id)
    {
        try {
            // Find the Interior color record
            $interior_color = InteriorColor::find($id);
    
            if (!$interior_color) {
                return response()->json(['message' => 'Interior Color Not Found'], 404);
            }
    
            // Delete the record
            $interior_color->delete();
    
            // Return success response
            return response()->json([
                'message' => 'Interior Color Deleted Successfully',
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json([
                'message' => 'An error occurred while deleting the Interior Color',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


}
