<?php

namespace Modules\Admin\Color\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\color\Models\color;

class ColorController extends Controller
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

        $color = Color::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Color Added Successfully',
            'color' => $color,
        ], status: 201);
    }

    public function index()
    {
        $color = Color::all();

        return response()->json([
            'message' => 'Colors data retrieved',
            'color' => $color,
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

        // Find the color record
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color Not Found'], 404);
        }

        // Update the record
        $color->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Color Updated Successfully',
            'color' => $color,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the color record
        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Color Not Found'], 404);
        }

        // Delete the record
        $color->delete();

        // Return success response
        return response()->json([
            'message' => 'Color Deleted Successfully',
        ], 200);
    }


}
