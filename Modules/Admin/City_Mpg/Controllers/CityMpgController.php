<?php

namespace Modules\Admin\City_Mpg\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\City_Mpg\Models\CityMpg;

class CityMpgController extends Controller
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
            $citympg = CityMpg::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'New City Mileage Added Successfully',
                'data' => $citympg,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add City Mileage. Please try again.', 'details' => $e->getMessage()], 500);
        }
    }


    public function index(Request $request)
    {
        try {
            // Determine the number of items per page
            if ($request->page === '0') {
                $perPage = CityMpg::count();
            } else {
                $perPage = $request->input('per_page', 10);
            }

            // Fetch paginated data
            $data = CityMpg::orderBy('created_at', 'desc')->paginate($perPage);

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
            return response()->json([
                'error' => 'Failed to retrieve data. Please try again.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            // Find CityMpg by ID
            $citympg = CityMpg::find($id);
    
            // Check if the record exists
            if (!$citympg) {
                return response()->json([
                    'message' => 'City Mileage not found',
                ], 404);
            }
    
            // Return success response if found
            return response()->json([
                'message' => 'City Mileage data retrieved successfully',
                'data' => $citympg,
            ], 200);
        } catch (Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'error' => 'An error occurred while retrieving data. Please try again.',
                'details' => $e->getMessage(),
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
    
        try {
            // Find the City Mileage record
            $citympg = CityMpg::find($id);
    
            if (!$citympg) {
                return response()->json(['message' => 'City Mileage Not Found'], 404);
            }
    
            // Update the record
            $citympg->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
    
            // Return success response
            return response()->json([
                'message' => 'City Mileage Updated Successfully',
                'data' => $citympg,
            ], 200);
    
        } catch (Exception $e) {
            // Log the exception (optional, depending on your logging configuration)
            // Log::error($e->getMessage());
    
            return response()->json([
                'error' => 'An error occurred while updating the City Mileage. Please try again later.',
                'details' => $e->getMessage(), // Optionally include the error details for debugging purposes
            ], 500);
        }
    }
    


    public function destroy($id)
    {
        try {
            // Find the City Mileage record
            $citympg = CityMpg::find($id);
    
            if (!$citympg) {
                return response()->json(['message' => 'City Mileage Not Found'], 404);
            }
    
            // Delete the record
            $citympg->delete();
    
            // Return success response
            return response()->json([
                'message' => 'City Mileage Deleted Successfully',
            ], 200);
        } catch (Exception $e) {
            // Catch any exceptions and return a generic error message
            return response()->json([
                'error' => 'Failed to delete City Mileage. Please try again later.',
                'details' => $e->getMessage()  // Optionally, add exception details for debugging
            ], 500);
        }
    }
    


}
