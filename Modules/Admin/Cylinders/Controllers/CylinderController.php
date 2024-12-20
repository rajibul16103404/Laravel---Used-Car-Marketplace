<?php

namespace Modules\Admin\Cylinders\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Cylinders\Models\Cylinder;

class CylinderController extends Controller
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

        $cylinder = Cylinder::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Cylinder Added Successfully',
            'data' => $cylinder,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $cylinder = Cylinder::all();

        // return response()->json([
        //     'message' => 'Cylinders data retrieved',
        //     'data' => $cylinder,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Cylinder::paginate($perPage);

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
        $cylinder = Cylinder::find($id);

        // Check if product exists
        if (!$cylinder) {
            return response()->json([
                'message' => 'Cylinder not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Cylinder data retrieved successfully',
            'data' => $cylinder,
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

        // Find the cylinder record
        $cylinder = Cylinder::find($id);

        if (!$cylinder) {
            return response()->json(['message' => 'Cylinder Not Found'], 404);
        }

        // Update the record
        $cylinder->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Cylinder Updated Successfully',
            'data' => $cylinder,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the cylinder record
        $cylinder = Cylinder::find($id);

        if (!$cylinder) {
            return response()->json(['message' => 'Cylinder Not Found'], 404);
        }

        // Delete the record
        $cylinder->delete();

        // Return success response
        return response()->json([
            'message' => 'Cylinder Deleted Successfully',
        ], 200);
    }


}
