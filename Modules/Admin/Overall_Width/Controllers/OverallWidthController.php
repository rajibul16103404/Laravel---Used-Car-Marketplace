<?php

namespace Modules\Admin\Overall_Width\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Overall_Width\Models\OverallWidth;

class OverallWidthController extends Controller
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

        $overall_width = OverallWidth::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Overall Width Added Successfully',
            'data' => $overall_width,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $overall_width = overall_width::all();

        // return response()->json([
        //     'message' => 'overall_width data retrieved',
        //     'data' => $overall_width,
        // ], 200);

        if($request->page === '0'){
            $perPage =  OverallWidth::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = OverallWidth::paginate($perPage);

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
        $overall_width = OverallWidth::find($id);

        // Check if product exists
        if (!$overall_width) {
            return response()->json([
                'message' => 'Overall Width not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Overall Width data retrieved successfully',
            'data' => $overall_width,
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

        // Find the Overall Width record
        $overall_width = OverallWidth::find($id);

        if (!$overall_width) {
            return response()->json(['message' => 'Overall Width Not Found'], 404);
        }

        // Update the record
        $overall_width->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Overall Width Updated Successfully',
            'data' => $overall_width,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Overall Width record
        $overall_width = OverallWidth::find($id);

        if (!$overall_width) {
            return response()->json(['message' => 'Overall Width Not Found'], 404);
        }

        // Delete the record
        $overall_width->delete();

        // Return success response
        return response()->json([
            'message' => 'Overall Width Deleted Successfully',
        ], 200);
    }


}
