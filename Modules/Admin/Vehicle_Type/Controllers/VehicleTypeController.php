<?php

namespace Modules\Admin\Vehicle_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Vehicle_Type\Models\VehicleType;

class VehicleTypeController extends Controller
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

        $vehicle_type = VehicleType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Vehicle Type Added Successfully',
            'data' => $vehicle_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $vehicle_type = vehicle_type::all();

        // return response()->json([
        //     'message' => 'vehicle_type data retrieved',
        //     'data' => $vehicle_type,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = VehicleType::paginate($perPage);

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
        $vehicle_type = VehicleType::find($id);

        // Check if product exists
        if (!$vehicle_type) {
            return response()->json([
                'message' => 'Vehicle Type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Vehicle Type data retrieved successfully',
            'data' => $vehicle_type,
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

        // Find the Vehicle Type record
        $vehicle_type = VehicleType::find($id);

        if (!$vehicle_type) {
            return response()->json(['message' => 'Vehicle Type Not Found'], 404);
        }

        // Update the record
        $vehicle_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Vehicle Type Updated Successfully',
            'data' => $vehicle_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Vehicle Type record
        $vehicle_type = VehicleType::find($id);

        if (!$vehicle_type) {
            return response()->json(['message' => 'Vehicle Type Not Found'], 404);
        }

        // Delete the record
        $vehicle_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Vehicle Type Deleted Successfully',
        ], 200);
    }


}
