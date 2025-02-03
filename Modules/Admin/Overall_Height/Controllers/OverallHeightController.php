<?php

namespace Modules\Admin\Overall_Height\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Overall_Height\Models\OverallHeight;

class OverallHeightController extends Controller
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

        $overall_height = OverallHeight::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Overall Height Added Successfully',
            'data' => $overall_height,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $overall_height = overall_height::all();

        // return response()->json([
        //     'message' => 'overall_height data retrieved',
        //     'data' => $overall_height,
        // ], 200);

        if($request->page === '0'){
            $perPage =  OverallHeight::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = OverallHeight::paginate($perPage);

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
        $overall_height = OverallHeight::find($id);

        // Check if product exists
        if (!$overall_height) {
            return response()->json([
                'message' => 'Overall Height not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Overall Height data retrieved successfully',
            'data' => $overall_height,
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

        // Find the Overall Height record
        $overall_height = OverallHeight::find($id);

        if (!$overall_height) {
            return response()->json(['message' => 'Overall Height Not Found'], 404);
        }

        // Update the record
        $overall_height->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Overall Height Updated Successfully',
            'data' => $overall_height,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Overall Height record
        $overall_height = OverallHeight::find($id);

        if (!$overall_height) {
            return response()->json(['message' => 'Overall Height Not Found'], 404);
        }

        // Delete the record
        $overall_height->delete();

        // Return success response
        return response()->json([
            'message' => 'Overall Height Deleted Successfully',
        ], 200);
    }


}
