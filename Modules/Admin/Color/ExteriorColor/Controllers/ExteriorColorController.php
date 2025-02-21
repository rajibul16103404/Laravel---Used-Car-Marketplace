<?php

namespace Modules\Admin\Color\ExteriorColor\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;

class ExteriorColorController extends Controller
{
    public function store(Request $request)
    {
        // Validate request input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);
    
        // If validation fails, return error response with 422
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            // Try to create the exterior color
            $exterior_color = ExteriorColor::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
    
            // Return success response
            return response()->json([
                'message' => 'New Exterior Color Added Successfully',
                'data' => $exterior_color,
            ], 201);
            
        } catch (Exception $e) {
            // Catch any exceptions that occur and return a 500 error response
            return response()->json(['error' => 'Failed to add Exterior Color. Please try again.'], 500);
        }
    }
    

    public function index(Request $request)
    {
        try {
            // Check if page is 0 and set perPage accordingly
            if($request->page === '0'){
                $perPage =  ExteriorColor::count();
            } else {
                $perPage = $request->input('per_page', 10);
            }
    
            // Retrieve data using pagination
            $data = ExteriorColor::orderBy('created_at', 'desc')->paginate($perPage);
    
            return response()->json([
                'pagination' => [
                    'total_count' => $data->total(),
                    'total_page' => $data->lastPage(),
                    'current_page' => $data->currentPage(),
                    'current_page_count' => $data->count(),
                    'next_page' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                    'previous_page' => $data->onFirstPage() ? null : $data->currentPage()
                ],
                'message' => 'Data Retrieved Successfully',
                'data' => $data->items(),
            ], 200);
    
        } catch (\Exception $e) {
            // Return a custom error response if any exception occurs
            return response()->json([
                'message' => 'An error occurred while retrieving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function show($id)
    {
        try {
            // Check if ID is valid (optional check based on your use case)
            if (!is_numeric($id)) {
                return response()->json([
                    'message' => 'Invalid ID format',
                ], 400);
            }
    
            // Find product by ID
            $exterior_color = ExteriorColor::find($id);
    
            // Check if product exists
            if (!$exterior_color) {
                return response()->json([
                    'message' => 'Exterior Color not found',
                ], 404);
            }
    
            return response()->json([
                'message' => 'Exterior Color data retrieved successfully',
                'data' => $exterior_color,
            ], 200);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'message' => 'An error occurred while retrieving data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $id)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|integer|in:0,1',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            // Find the Exterior Color record
            $exterior_color = ExteriorColor::find($id);
    
            if (!$exterior_color) {
                return response()->json(['message' => 'Exterior Color Not Found'], 404);
            }
    
            // Update the record
            $updated = $exterior_color->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
    
            // Check if the update was successful
            if (!$updated) {
                return response()->json(['message' => 'Failed to Update Exterior Color'], 500);
            }
    
            // Return success response
            return response()->json([
                'message' => 'Exterior Color Updated Successfully',
                'data' => $exterior_color,
            ], 200);
        } catch (\Exception $e) {
            // Catch any unexpected errors
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    


    public function destroy($id)
    {
        try {
            // Find the Exterior Color record
            $exterior_color = ExteriorColor::find($id);
    
            if (!$exterior_color) {
                return response()->json(['message' => 'Exterior Color Not Found'], 404);
            }
    
            // Delete the record
            $exterior_color->delete();
    
            // Return success response
            return response()->json([
                'message' => 'Exterior Color Deleted Successfully',
            ], 200);
        } catch (\Exception $e) {
            // Catch any unexpected exceptions and return an error response
            return response()->json([
                'message' => 'An error occurred while deleting the Exterior Color',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    


}
