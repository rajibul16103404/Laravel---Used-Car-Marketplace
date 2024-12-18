<?php

namespace Modules\Admin\Make\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Make\Models\Make;

class MakeController extends Controller
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

        $make = Make::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Make Added Successfully',
            'make' => $make,
        ], status: 201);
    }

    public function index()
    {
        $make = Make::all();

        return response()->json([
            'message' => 'Makes data retrieved',
            'make' => $make,
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

        // Find the make record
        $make = Make::find($id);

        if (!$make) {
            return response()->json(['message' => 'Make Not Found'], 404);
        }

        // Update the record
        $make->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Make Updated Successfully',
            'make' => $make,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the make record
        $make = Make::find($id);

        if (!$make) {
            return response()->json(['message' => 'Make Not Found'], 404);
        }

        // Delete the record
        $make->delete();

        // Return success response
        return response()->json([
            'message' => 'Make Deleted Successfully',
        ], 200);
    }


}
