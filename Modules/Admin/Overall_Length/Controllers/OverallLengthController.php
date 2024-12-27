<?php

namespace Modules\Admin\Overall_Length\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Overall_Length\Models\OverallLength;

class OverallLengthController extends Controller
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

        $overall_length = OverallLength::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Overall Length Added Successfully',
            'data' => $overall_length,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $overall_length = overall_length::all();

        // return response()->json([
        //     'message' => 'overall_length data retrieved',
        //     'data' => $overall_length,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = OverallLength::paginate($perPage);

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
        $overall_length = OverallLength::find($id);

        // Check if product exists
        if (!$overall_length) {
            return response()->json([
                'message' => 'overall_length not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Overall Length data retrieved successfully',
            'data' => $overall_length,
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

        // Find the Overall Length record
        $overall_length = OverallLength::find($id);

        if (!$overall_length) {
            return response()->json(['message' => 'Overall Length Not Found'], 404);
        }

        // Update the record
        $overall_length->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Overall Length Updated Successfully',
            'data' => $overall_length,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Overall Length record
        $overall_length = OverallLength::find($id);

        if (!$overall_length) {
            return response()->json(['message' => 'Overall Length Not Found'], 404);
        }

        // Delete the record
        $overall_length->delete();

        // Return success response
        return response()->json([
            'message' => 'Overall Length Deleted Successfully',
        ], 200);
    }


}
