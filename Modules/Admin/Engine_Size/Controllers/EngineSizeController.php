<?php

namespace Modules\Admin\Engine_Size\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Engine_Size\Models\EngineSize;

class EngineSizeController extends Controller
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

        $engine_size = EngineSize::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Engine Size Added Successfully',
            'data' => $engine_size,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $engine_size = engine_size::all();

        // return response()->json([
        //     'message' => 'engine_size data retrieved',
        //     'data' => $engine_size,
        // ], 200);

        if($request->page === '0'){
            $perPage =  EngineSize::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = EngineSize::paginate($perPage);

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
        $engine_size = EngineSize::find($id);

        // Check if product exists
        if (!$engine_size) {
            return response()->json([
                'message' => 'Engine Size not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Engine Size data retrieved successfully',
            'data' => $engine_size,
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

        // Find the engine_size record
        $engine_size = EngineSize::find($id);

        if (!$engine_size) {
            return response()->json(['message' => 'Engine Size Not Found'], 404);
        }

        // Update the record
        $engine_size->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Engine Size Updated Successfully',
            'data' => $engine_size,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the engine_size record
        $engine_size = EngineSize::find($id);

        if (!$engine_size) {
            return response()->json(['message' => 'Engine Size Not Found'], 404);
        }

        // Delete the record
        $engine_size->delete();

        // Return success response
        return response()->json([
            'message' => 'Engine Size Deleted Successfully',
        ], 200);
    }


}
