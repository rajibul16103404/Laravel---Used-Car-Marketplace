<?php

namespace Modules\Admin\Body_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Type\Models\Body_Type;

class Body_TypeController extends Controller
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

        $body_type = Body_Type::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Body Type Added Successfully',
            'body_type' => $body_type,
        ], status: 201);
    }

    public function index()
    {
        $body_type = Body_Type::all();

        return response()->json([
            'message' => 'Body Types data retrieved',
            'body_type' => $body_type,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());
        var_dump($request->all());


        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // Find the body type record
        $body_type = Body_Type::find($id);

        if (!$body_type) {
            return response()->json(['message' => 'Body Type Not Found'], 404);
        }

        // Update the record
        $body_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Body Type Updated Successfully',
            'body_type' => $body_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the body type record
        $body_type = Body_Type::find($id);

        if (!$body_type) {
            return response()->json(['message' => 'Body Type Not Found'], 404);
        }

        // Delete the record
        $body_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Body Type Deleted Successfully',
        ], 200);
    }


}
