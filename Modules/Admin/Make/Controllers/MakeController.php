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
            'data' => $make,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $make = Make::all();

        // return response()->json([
        //     'message' => 'Makes data retrieved',
        //     'data' => $make,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Make::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Make::paginate($perPage);

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
        $make = Make::find($id);
    
        // Check if product exists
        if (!$make) {
            return response()->json([
                'message' => 'Make not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'Make data retrieved successfully',
            'data' => $make,
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
            'data' => $make,
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
