<?php

namespace Modules\Admin\Body_Subtype\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Subtype\Models\BodySubType;

class BodySubTypeController extends Controller
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

        $bodysubtype = BodySubType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Body Sub Type Added Successfully',
            'data' => $bodysubtype,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $bodysubtype = bodysubtype::all();

        // return response()->json([
        //     'message' => 'bodysubtype data retrieved',
        //     'data' => $bodysubtype,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = BodySubType::paginate($perPage);

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
        $bodysubtype = BodySubType::find($id);

        // Check if product exists
        if (!$bodysubtype) {
            return response()->json([
                'message' => 'Body Sub Type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Body Sub Type data retrieved successfully',
            'data' => $bodysubtype,
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

        // Find the Body Sub Type record
        $bodysubtype = BodySubType::find($id);

        if (!$bodysubtype) {
            return response()->json(['message' => 'Body Sub Type Not Found'], 404);
        }

        // Update the record
        $bodysubtype->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Body Sub Type Updated Successfully',
            'data' => $bodysubtype,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Body Sub Type record
        $bodysubtype = BodySubType::find($id);

        if (!$bodysubtype) {
            return response()->json(['message' => 'Body Sub Type Not Found'], 404);
        }

        // Delete the record
        $bodysubtype->delete();

        // Return success response
        return response()->json([
            'message' => 'Body Sub Type Deleted Successfully',
        ], 200);
    }


}
