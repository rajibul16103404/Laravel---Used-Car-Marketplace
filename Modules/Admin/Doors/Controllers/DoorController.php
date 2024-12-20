<?php

namespace Modules\Admin\Doors\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Doors\Models\Door;

class DoorController extends Controller
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

        $door = Door::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Door Added Successfully',
            'data' => $door,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $door = Door::all();

        // return response()->json([
        //     'message' => 'Doors data retrieved',
        //     'data' => $door,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Door::paginate($perPage);

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
        $door = Door::find($id);

        // Check if product exists
        if (!$door) {
            return response()->json([
                'message' => 'Door not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Door data retrieved successfully',
            'data' => $door,
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

        // Find the door record
        $door = Door::find($id);

        if (!$door) {
            return response()->json(['message' => 'Door Not Found'], 404);
        }

        // Update the record
        $door->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Door Updated Successfully',
            'data' => $door,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the door record
        $door = Door::find($id);

        if (!$door) {
            return response()->json(['message' => 'Door Not Found'], 404);
        }

        // Delete the record
        $door->delete();

        // Return success response
        return response()->json([
            'message' => 'Door Deleted Successfully',
        ], 200);
    }


}
