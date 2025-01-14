<?php

namespace Modules\Admin\Highway_Mpg\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Highway_Mpg\Models\HighwayMpg;

class HighwayMpgController extends Controller
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

        $highway_mpg = HighwayMpg::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Highway Mpg Added Successfully',
            'data' => $highway_mpg,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $highway_mpg = highway_mpg::all();

        // return response()->json([
        //     'message' => 'highway_mpg data retrieved',
        //     'data' => $highway_mpg,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = HighwayMpg::paginate($perPage);

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
        $highway_mpg = HighwayMpg::find($id);

        // Check if product exists
        if (!$highway_mpg) {
            return response()->json([
                'message' => 'Highway Mpg not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Highway Mpg data retrieved successfully',
            'data' => $highway_mpg,
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

        // Find the highway_mpg record
        $highway_mpg = HighwayMpg::find($id);

        if (!$highway_mpg) {
            return response()->json(['message' => 'Highway Mpg Not Found'], 404);
        }

        // Update the record
        $highway_mpg->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Highway Mpg Updated Successfully',
            'data' => $highway_mpg,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the highway_mpg record
        $highway_mpg = HighwayMpg::find($id);

        if (!$highway_mpg) {
            return response()->json(['message' => 'Highway Mpg Not Found'], 404);
        }

        // Delete the record
        $highway_mpg->delete();

        // Return success response
        return response()->json([
            'message' => 'Highway Mpg Deleted Successfully',
        ], 200);
    }


}
