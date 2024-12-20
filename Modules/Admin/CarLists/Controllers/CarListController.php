<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;

class CarListController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'seller_id' => 'required|integer|max:255',
            'title' => 'required|string|max:255',
            'make_id' => 'required|integer|max:255',
            'model_id' => 'required|integer|max:255',
            'body_type_id' => 'required|integer|max:255',
            'drive_type_id' => 'required|integer|max:255',
            'transmission_id' => 'required|integer|max:255',
            'condition_id' => 'required|integer|max:255',
            'year' => 'required|integer|max:255',
            'fuel_type_id' => 'required|integer|max:255',
            'engine_size' => 'required|string|max:255',
            'door_id' => 'required|integer|max:255',
            'cylinder_id' => 'required|integer|max:255',
            'color_id' => 'required|integer|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|float|max:255',
            'safety_features' => 'required|string|max:255',
            'key_features' => 'required|string|max:255',
            'category_id' => 'required|integer|max:255',
            'imageURL' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $carlist = Carlist::create([
            'seller_id' => $request->seller_id,
            'title' => $request->title,
            'make_id' => $request->make_id,
            'model_id' => $request->model_id,
            'body_type_id' => $request->body_type_id,
            'drive_type_id' => $request->drive_type_id,
            'transmission_id' => $request->transmission_id,
            'condition_id' => $request->condition_id,
            'year' => $request->year,
            'fuel_type_id' => $request->fuel_type_id,
            'engine_size' => $request->engine_size,
            'door_id' => $request->door_id,
            'cylinder_id' => $request->cylinder_id,
            'color_id' => $request->color_id,
            'description' => $request->description,
            'price' => $request->price,
            'safety_features' => $request->safety_features,
            'key_features' => $request->key_features,
            'category_id' => $request->category_id,
            'imageURL' => $request->imageURL,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Car List Added Successfully',
            'data' => $carlist,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $carlist = Carlist::all();

        // return response()->json([
        //     'message' => 'Car Lists data retrieved',
        //     'data' => $carlist,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Carlist::paginate($perPage);

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
        $car_list = Carlist::find($id);

        // Check if product exists
        if (!$car_list) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Car data retrieved successfully',
            'data' => $car_list,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'make_id' => 'required|integer|max:255',
            'model_id' => 'required|integer|max:255',
            'body_type_id' => 'required|integer|max:255',
            'drive_type_id' => 'required|integer|max:255',
            'transmission_id' => 'required|integer|max:255',
            'condition_id' => 'required|integer|max:255',
            'year' => 'required|integer|max:255',
            'fuel_type_id' => 'required|integer|max:255',
            'engine_size' => 'required|string|max:255',
            'door_id' => 'required|integer|max:255',
            'cylinder_id' => 'required|integer|max:255',
            'color_id' => 'required|integer|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|float|max:255',
            'safety_features' => 'required|string|max:255',
            'key_features' => 'required|string|max:255',
            'category_id' => 'required|integer|max:255',
            'imageURL' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the carlist record
        $carlist = Carlist::find($id);

        if (!$carlist) {
            return response()->json(['message' => 'Car List Not Found'], 404);
        }

        // Update the record
        $carlist->update([
            'title' => $request->title,
            'make_id' => $request->make_id,
            'model_id' => $request->model_id,
            'body_type_id' => $request->body_type_id,
            'drive_type_id' => $request->drive_type_id,
            'transmission_id' => $request->transmission_id,
            'condition_id' => $request->condition_id,
            'year' => $request->year,
            'fuel_type_id' => $request->fuel_type_id,
            'engine_size' => $request->engine_size,
            'door_id' => $request->door_id,
            'cylinder_id' => $request->cylinder_id,
            'color_id' => $request->color_id,
            'description' => $request->description,
            'price' => $request->price,
            'safety_features' => $request->safety_features,
            'key_features' => $request->key_features,
            'category_id' => $request->category_id,
            'imageURL' => $request->imageURL,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Car List Updated Successfully',
            'data' => $carlist,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the carlist record
        $carlist = Carlist::find($id);

        if (!$carlist) {
            return response()->json(['message' => 'Car List Not Found'], 404);
        }

        // Delete the record
        $carlist->delete();

        // Return success response
        return response()->json([
            'message' => 'Car List Deleted Successfully',
        ], 200);
    }


}
