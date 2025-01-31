<?php

namespace Modules\Admin\Transmission\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Transmission\Models\Transmission;

class TransmissionController extends Controller
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

        $transmission = Transmission::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Transmission Added Successfully',
            'data' => $transmission,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $transmission = Transmission::all();

        // return response()->json([
        //     'message' => 'Transmissions data retrieved',
        //     'data' => $transmission,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Transmission::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Transmission::paginate($perPage);

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
        $transmission = Transmission::find($id);
    
        // Check if product exists
        if (!$transmission) {
            return response()->json([
                'message' => 'Transmission not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'Transmission data retrieved successfully',
            'data' => $transmission,
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

        // Find the transmission record
        $transmission = Transmission::find($id);

        if (!$transmission) {
            return response()->json(['message' => 'Transmission Not Found'], 404);
        }

        // Update the record
        $transmission->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Transmission Updated Successfully',
            'data' => $transmission,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the transmission record
        $transmission = Transmission::find($id);

        if (!$transmission) {
            return response()->json(['message' => 'Transmission Not Found'], 404);
        }

        // Delete the record
        $transmission->delete();

        // Return success response
        return response()->json([
            'message' => 'Transmission Deleted Successfully',
        ], 200);
    }


}
