<?php

namespace Modules\Admin\Color\ExteriorColor\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;

class ExteriorColorController extends Controller
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

        $exterior_color = ExteriorColor::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Exterior Color Added Successfully',
            'data' => $exterior_color,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $color = Color::all();

        // return response()->json([
        //     'message' => 'Colors data retrieved',
        //     'data' => $color,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = ExteriorColor::paginate($perPage);

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
        $exterior_color = ExteriorColor::find($id);

        // Check if product exists
        if (!$exterior_color) {
            return response()->json([
                'message' => 'Exterior Color not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Exterior Color data retrieved successfully',
            'data' => $exterior_color,
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

        // Find the Exterior Color record
        $exterior_color = ExteriorColor::find($id);

        if (!$exterior_color) {
            return response()->json(['message' => 'Exterior Color Not Found'], 404);
        }

        // Update the record
        $exterior_color->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Exterior Color Updated Successfully',
            'data' => $exterior_color,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Exterior Color record
        $exterior_color = ExteriorColor::find($id);

        if (!$exterior_color) {
            return response()->json(['message' => 'Exterior Color Not Found'], 404);
        }

        // Delete the record
        $exterior_color->delete();

        // Return success response
        return response()->json([
            'message' => 'Exterior Color Deleted Successfully',
        ], 200);
    }


}
