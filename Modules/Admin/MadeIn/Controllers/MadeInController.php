<?php

namespace Modules\Admin\MadeIn\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\MadeIn\Models\MadeIn;

class MadeInController extends Controller
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

        $made_in = MadeIn::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Made In Added Successfully',
            'data' => $made_in,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $made_in = made_in::all();

        // return response()->json([
        //     'message' => 'made_in data retrieved',
        //     'data' => $made_in,
        // ], 200);

        if($request->page === '0'){
            $perPage =  MadeIn::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = MadeIn::paginate($perPage);

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
        $made_in = MadeIn::find($id);

        // Check if product exists
        if (!$made_in) {
            return response()->json([
                'message' => 'Made In not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Made In data retrieved successfully',
            'data' => $made_in,
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

        // Find the Made In record
        $made_in = MadeIn::find($id);

        if (!$made_in) {
            return response()->json(['message' => 'Made In Not Found'], 404);
        }

        // Update the record
        $made_in->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Made In Updated Successfully',
            'data' => $made_in,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Made In record
        $made_in = MadeIn::find($id);

        if (!$made_in) {
            return response()->json(['message' => 'Made In Not Found'], 404);
        }

        // Delete the record
        $made_in->delete();

        // Return success response
        return response()->json([
            'message' => 'Made In Deleted Successfully',
        ], 200);
    }


}
