<?php

namespace Modules\Admin\Fuel_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Fuel_Type\Models\Fuel_type;

class Fuel_TypeController extends Controller
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

        $fuel_type = Fuel_type::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Fuel type Added Successfully',
            'fuel_type' => $fuel_type,
        ], status: 201);
    }

    public function index()
    {
        $fuel_type = Fuel_type::all();

        return response()->json([
            'message' => 'Fuel types data retrieved',
            'fuel_type' => $fuel_type,
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

        // Find the fuel_type record
        $fuel_type = Fuel_type::find($id);

        if (!$fuel_type) {
            return response()->json(['message' => 'Fuel type Not Found'], 404);
        }

        // Update the record
        $fuel_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Fuel type Updated Successfully',
            'fuel_type' => $fuel_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the fuel_type record
        $fuel_type = Fuel_type::find($id);

        if (!$fuel_type) {
            return response()->json(['message' => 'Fuel type Not Found'], 404);
        }

        // Delete the record
        $fuel_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Fuel type Deleted Successfully',
        ], 200);
    }


}
