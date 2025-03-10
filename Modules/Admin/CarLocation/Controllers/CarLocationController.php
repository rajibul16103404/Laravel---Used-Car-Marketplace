<?php

namespace Modules\Admin\CarLocation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLocation\Models\CarLocation;

class CarLocationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = CarLocation::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Car Location Added Successfully',
            'data' => $category,
        ], status: 201);
    }

    public function index(Request $request)
    {
        if($request->page === '0'){
            $perPage =  CarLocation::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = CarLocation::paginate($perPage);

        return response()->json([
            'pagination' => [
                'total_count'=>$data->total(),
                'total_page'=>$data->lastPage(),
                'current_page'=>$data->currentPage(),
                'current_page_count'=>$data->count(),
                'next_page' => $data->hasMorePages() ? $data->currentPage()+1 : null,
                'previous_page'=>$data->onFirstPage() ? null : $data->currentPage()
            ],
            'message' => 'Data Retrieved Successfully',
            'data' => $data->items(),
        ],200);
    }

    public function show($id)
    {
        // Find product by ID
        $carLocation = CarLocation::find($id);

        // Check if product exists
        if (!$carLocation) {
            return response()->json([
                'message' => 'Car Location not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Car Location data retrieved successfully',
            'data' => $carLocation,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Category record
        $carLocation = CarLocation::find($id);

        if (!$carLocation) {
            return response()->json(['message' => 'Car Location Not Found'], 404);
        }

        // Update the record
        $carLocation->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Car Location Updated Successfully',
            'data' => $carLocation,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Category record
        $carLocation = CarLocation::find($id);

        if (!$carLocation) {
            return response()->json(['message' => 'Car Location Not Found'], 404);
        }

        // Delete the record
        $carLocation->delete();

        // Return success response
        return response()->json([
            'message' => 'Car Location Deleted Successfully',
        ], 200);
    }


}
