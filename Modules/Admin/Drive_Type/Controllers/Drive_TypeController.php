<?php

namespace Modules\Admin\Drive_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Drive_Type\Models\Drive_type;

class Drive_TypeController extends Controller
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

        $drive_type = Drive_type::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Drive type Added Successfully',
            'drive_type' => $drive_type,
        ], status: 201);
    }

    public function index()
    {
        $drive_type = Drive_type::all();

        return response()->json([
            'message' => 'Drive types data retrieved',
            'drive_type' => $drive_type,
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

        // Find the drive_type record
        $drive_type = Drive_type::find($id);

        if (!$drive_type) {
            return response()->json(['message' => 'Drive type Not Found'], 404);
        }

        // Update the record
        $drive_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Drive type Updated Successfully',
            'drive_type' => $drive_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the drive_type record
        $drive_type = Drive_type::find($id);

        if (!$drive_type) {
            return response()->json(['message' => 'Drive type Not Found'], 404);
        }

        // Delete the record
        $drive_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Drive type Deleted Successfully',
        ], 200);
    }


}
