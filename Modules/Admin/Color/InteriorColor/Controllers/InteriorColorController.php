<?php

namespace Modules\Admin\Color\InteriorColor\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;

class InteriorColorController extends Controller
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

        $interior_color = InteriorColor::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Interior Color Added Successfully',
            'data' => $interior_color,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $color = Color::all();

        // return response()->json([
        //     'message' => 'Colors data retrieved',
        //     'data' => $color,
        // ], 200);

        if($request->page === '0'){
            $perPage =  InteriorColor::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = InteriorColor::paginate($perPage);

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
        $interior_color = InteriorColor::find($id);

        // Check if product exists
        if (!$interior_color) {
            return response()->json([
                'message' => 'Interior Color not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Interior Color data retrieved successfully',
            'data' => $interior_color,
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

        // Find the Interior color record
        $interior_color = InteriorColor::find($id);

        if (!$interior_color) {
            return response()->json(['message' => 'Interior Color Not Found'], 404);
        }

        // Update the record
        $interior_color->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Interior Color Updated Successfully',
            'data' => $interior_color,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Interior color record
        $interior_color = InteriorColor::find($id);

        if (!$interior_color) {
            return response()->json(['message' => 'Interior Color Not Found'], 404);
        }

        // Delete the record
        $interior_color->delete();

        // Return success response
        return response()->json([
            'message' => 'Interior Color Deleted Successfully',
        ], 200);
    }


}
