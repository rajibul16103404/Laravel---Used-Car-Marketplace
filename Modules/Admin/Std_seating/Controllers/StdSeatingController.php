<?php

namespace Modules\Admin\Std_seating\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Std_seating\Models\StdSeating;

class StdSeatingController extends Controller
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

        $std_seating = StdSeating::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New STD Seating Added Successfully',
            'data' => $std_seating,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $std_seating = std_seating::all();

        // return response()->json([
        //     'message' => 'std_seating data retrieved',
        //     'data' => $std_seating,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = StdSeating::paginate($perPage);

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
        $std_seating = StdSeating::find($id);

        // Check if product exists
        if (!$std_seating) {
            return response()->json([
                'message' => 'STD Seating not found',
            ], 404);
        }

        return response()->json([
            'message' => 'STD Seating data retrieved successfully',
            'data' => $std_seating,
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

        // Find the STD Seating record
        $std_seating = StdSeating::find($id);

        if (!$std_seating) {
            return response()->json(['message' => 'STD Seating Not Found'], 404);
        }

        // Update the record
        $std_seating->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'STD Seating Updated Successfully',
            'data' => $std_seating,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the STD Seating record
        $std_seating = StdSeating::find($id);

        if (!$std_seating) {
            return response()->json(['message' => 'STD Seating Not Found'], 404);
        }

        // Delete the record
        $std_seating->delete();

        // Return success response
        return response()->json([
            'message' => 'STD Seating Deleted Successfully',
        ], 200);
    }


}
