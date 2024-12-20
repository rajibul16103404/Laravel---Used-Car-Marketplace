<?php

namespace Modules\Admin\Body_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Type\Models\Body_Type;

class Body_TypeController extends Controller
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

        $body_type = Body_Type::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Body Type Added Successfully',
            'data' => $body_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $body_type = Body_Type::all();

        // return response()->json([
        //     'message' => 'Body Types data retrieved',
        //     'body_type' => $body_type,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Body_Type::paginate($perPage);

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
    $body_type = Body_Type::find($id);

    // Check if product exists
    if (!$body_type) {
        return response()->json([
            'message' => 'Body Type not found',
        ], 404);
    }

    return response()->json([
        'message' => 'Body Type data retrieved successfully',
        'data' => $body_type,
    ], 200);
}


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());
        // var_dump($request->all());


        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // Find the body type record
        $body_type = Body_Type::find($id);

        if (!$body_type) {
            return response()->json(['message' => 'Body Type Not Found'], 404);
        }

        // Update the record
        $body_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Body Type Updated Successfully',
            'data' => $body_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the body type record
        $body_type = Body_Type::find($id);

        if (!$body_type) {
            return response()->json(['message' => 'Body Type Not Found'], 404);
        }

        // Delete the record
        $body_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Body Type Deleted Successfully',
        ], 200);
    }


    public function pagination(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $data = Body_Type::paginate($perPage);

        return response()->json([
            'message' => 'Body Type Data Retrieved Successfully',
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ]
        ],200);
    }

}
