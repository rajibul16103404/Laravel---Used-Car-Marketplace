<?php

namespace Modules\Admin\City_Mpg\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\City_Mpg\Models\CityMpg;

class CityMpgController extends Controller
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

        $citympg = CityMpg::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New City Mileage Added Successfully',
            'data' => $citympg,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $citympg = citympg::all();

        // return response()->json([
        //     'message' => 'City Mileage data retrieved',
        //     'data' => $citympg,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = CityMpg::paginate($perPage);

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
        $citympg = CityMpg::find($id);

        // Check if product exists
        if (!$citympg) {
            return response()->json([
                'message' => 'City Mileage not found',
            ], 404);
        }

        return response()->json([
            'message' => 'City Mileage data retrieved successfully',
            'data' => $citympg,
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

        // Find the City Mileage record
        $citympg = CityMpg::find($id);

        if (!$citympg) {
            return response()->json(['message' => 'City Mileage Not Found'], 404);
        }

        // Update the record
        $citympg->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'City Mileage Updated Successfully',
            'data' => $citympg,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the City Mileage record
        $citympg = CityMpg::find($id);

        if (!$citympg) {
            return response()->json(['message' => 'City Mileage Not Found'], 404);
        }

        // Delete the record
        $citympg->delete();

        // Return success response
        return response()->json([
            'message' => 'City Mileage Deleted Successfully',
        ], 200);
    }


}
