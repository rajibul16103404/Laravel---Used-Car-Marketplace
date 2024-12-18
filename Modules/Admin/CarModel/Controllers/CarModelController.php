<?php

namespace Modules\Admin\CarModel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarModel\Models\Carmodel;

class CarModelController extends Controller
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

        $carmodel = Carmodel::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Car Model Added Successfully',
            'carmodel' => $carmodel,
        ], status: 201);
    }

    public function index()
    {
        $carmodel = Carmodel::all();

        return response()->json([
            'message' => 'Car Models data retrieved',
            'carmodel' => $carmodel,
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

        // Find the carmodel record
        $carmodel = Carmodel::find($id);

        if (!$carmodel) {
            return response()->json(['message' => 'Car Model Not Found'], 404);
        }

        // Update the record
        $carmodel->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Car Model Updated Successfully',
            'carmodel' => $carmodel,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the carmodel record
        $carmodel = Carmodel::find($id);

        if (!$carmodel) {
            return response()->json(['message' => 'Car Model Not Found'], 404);
        }

        // Delete the record
        $carmodel->delete();

        // Return success response
        return response()->json([
            'message' => 'Car Model Deleted Successfully',
        ], 200);
    }


}
