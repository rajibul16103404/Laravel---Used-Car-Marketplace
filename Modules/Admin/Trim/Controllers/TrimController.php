<?php

namespace Modules\Admin\Trim\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Trim\Models\Trim;

class TrimController extends Controller
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

        $trim = Trim::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Trim Added Successfully',
            'data' => $trim,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $trim = trim::all();

        // return response()->json([
        //     'message' => 'trim data retrieved',
        //     'data' => $trim,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Trim::paginate($perPage);

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
        $trim = Trim::find($id);

        // Check if product exists
        if (!$trim) {
            return response()->json([
                'message' => 'trim not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Trim data retrieved successfully',
            'data' => $trim,
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

        // Find the Trim record
        $trim = Trim::find($id);

        if (!$trim) {
            return response()->json(['message' => 'Trim Not Found'], 404);
        }

        // Update the record
        $trim->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Trim Updated Successfully',
            'data' => $trim,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Trim record
        $trim = Trim::find($id);

        if (!$trim) {
            return response()->json(['message' => 'Trim Not Found'], 404);
        }

        // Delete the record
        $trim->delete();

        // Return success response
        return response()->json([
            'message' => 'Trim Deleted Successfully',
        ], 200);
    }


}
