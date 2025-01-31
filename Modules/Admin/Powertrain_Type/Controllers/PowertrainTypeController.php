<?php

namespace Modules\Admin\Powertrain_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;

class PowertrainTypeController extends Controller
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

        $powertrain_type = PowertrainType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Powertrain Type Added Successfully',
            'data' => $powertrain_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $powertrain_type = powertrain_type::all();

        // return response()->json([
        //     'message' => 'powertrain_type data retrieved',
        //     'data' => $powertrain_type,
        // ], 200);

        if($request->page === '0'){
            $perPage =  PowertrainType::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = PowertrainType::paginate($perPage);

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
        $powertrain_type = PowertrainType::find($id);

        // Check if product exists
        if (!$powertrain_type) {
            return response()->json([
                'message' => 'Powertrain Type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Powertrain Type data retrieved successfully',
            'data' => $powertrain_type,
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

        // Find the Powertrain Type record
        $powertrain_type = PowertrainType::find($id);

        if (!$powertrain_type) {
            return response()->json(['message' => 'Powertrain Type Not Found'], 404);
        }

        // Update the record
        $powertrain_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Powertrain Type Updated Successfully',
            'data' => $powertrain_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Powertrain Type record
        $powertrain_type = PowertrainType::find($id);

        if (!$powertrain_type) {
            return response()->json(['message' => 'Powertrain Type Not Found'], 404);
        }

        // Delete the record
        $powertrain_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Powertrain Type Deleted Successfully',
        ], 200);
    }


}
