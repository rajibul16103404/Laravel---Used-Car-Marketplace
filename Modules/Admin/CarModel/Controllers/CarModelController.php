<?php

namespace Modules\Admin\CarModel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarModel\Models\Carmodel;

class CarModelController extends Controller
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

        $carmodel = Carmodel::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Car Model Added Successfully',
            'data' => $carmodel,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $carmodel = Carmodel::all();

        // return response()->json([
        //     'message' => 'Car Models data retrieved',
        //     'data' => $carmodel,
        // ], 200);

        // Check if the request is for the first page
        if($request->page === '0'){
            $perPage =  Carmodel::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Carmodel::paginate($perPage);

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
        $car_model = Carmodel::find($id);

        // Check if product exists
        if (!$car_model) {
            return response()->json([
                'message' => 'Car Model not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Car Model data retrieved successfully',
            'data' => $car_model,
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

        // Find the carmodel record
        $carmodel = Carmodel::find($id);

        if (!$carmodel) {
            return response()->json(['message' => 'Car Model Not Found'], 404);
        }

        // Update the record
        $carmodel->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Car Model Updated Successfully',
            'data' => $carmodel,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the carmodel record
        $carmodel = Carmodel::find($id);

        if (!$carmodel) {
            return response()->json(['message' => 'Car Model Not Found'], 404);
        }

        // Delete the record
        $carmodel->delete();

        // Return success response
        return response()->json([
            'message' => 'Car Model Deleted Successfully',
        ], 200);
    }


}
