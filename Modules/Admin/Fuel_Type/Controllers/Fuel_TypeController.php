<?php

namespace Modules\Admin\Fuel_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Fuel_Type\Models\Fuel_type;

class Fuel_TypeController extends Controller
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

        $fuel_type = Fuel_type::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Fuel type Added Successfully',
            'data' => $fuel_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $fuel_type = Fuel_type::all();

        // return response()->json([
        //     'message' => 'Fuel types data retrieved',
        //     'data' => $fuel_type,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Fuel_type::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Fuel_type::paginate($perPage);

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
        $fuel_type = Fuel_type::find($id);
    
        // Check if product exists
        if (!$fuel_type) {
            return response()->json([
                'message' => 'Fuel Type not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'Fuel Type data retrieved successfully',
            'data' => $fuel_type,
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

        // Find the fuel_type record
        $fuel_type = Fuel_type::find($id);

        if (!$fuel_type) {
            return response()->json(['message' => 'Fuel type Not Found'], 404);
        }

        // Update the record
        $fuel_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Fuel type Updated Successfully',
            'data' => $fuel_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the fuel_type record
        $fuel_type = Fuel_type::find($id);

        if (!$fuel_type) {
            return response()->json(['message' => 'Fuel type Not Found'], 404);
        }

        // Delete the record
        $fuel_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Fuel type Deleted Successfully',
        ], 200);
    }


}
