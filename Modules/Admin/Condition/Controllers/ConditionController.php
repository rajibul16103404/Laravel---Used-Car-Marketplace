<?php

namespace Modules\Admin\Condition\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Condition\Models\Condition;

class ConditionController extends Controller
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

        $condition = Condition::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Condition Added Successfully',
            'condition' => $condition,
        ], status: 201);
    }

    public function index()
    {
        $condition = Condition::all();

        return response()->json([
            'message' => 'Conditions data retrieved',
            'condition' => $condition,
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

        // Find the condition record
        $condition = Condition::find($id);

        if (!$condition) {
            return response()->json(['message' => 'Condition Not Found'], 404);
        }

        // Update the record
        $condition->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Condition Updated Successfully',
            'condition' => $condition,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the condition record
        $condition = Condition::find($id);

        if (!$condition) {
            return response()->json(['message' => 'Condition Not Found'], 404);
        }

        // Delete the record
        $condition->delete();

        // Return success response
        return response()->json([
            'message' => 'Condition Deleted Successfully',
        ], 200);
    }


}
