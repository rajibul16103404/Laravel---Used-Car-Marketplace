<?php

namespace Modules\Admin\Engine\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Engine\Models\Engine;

class EngineController extends Controller
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

        $engine = Engine::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Engine Added Successfully',
            'data' => $engine,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $engine = engine::all();

        // return response()->json([
        //     'message' => 'engine data retrieved',
        //     'data' => $engine,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Engine::paginate($perPage);

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
        $engine = Engine::find($id);

        // Check if product exists
        if (!$engine) {
            return response()->json([
                'message' => 'Engine not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Engine data retrieved successfully',
            'data' => $engine,
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

        // Find the Engine record
        $engine = Engine::find($id);

        if (!$engine) {
            return response()->json(['message' => 'Engine Not Found'], 404);
        }

        // Update the record
        $engine->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Engine Updated Successfully',
            'data' => $engine,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Engine record
        $engine = Engine::find($id);

        if (!$engine) {
            return response()->json(['message' => 'Engine Not Found'], 404);
        }

        // Delete the record
        $engine->delete();

        // Return success response
        return response()->json([
            'message' => 'Engine Deleted Successfully',
        ], 200);
    }


}
