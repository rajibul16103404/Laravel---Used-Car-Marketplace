<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Category\Models\Category;
use Modules\Admin\Color\Models\Color;
use Modules\Admin\Condition\Models\Condition;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\Drive_Type\Models\Drive_type;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Transmission\Models\Transmission;

class CarListController extends Controller
{
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(),[
        //     'seller_id' => 'required|integer|max:255',
        //     'title' => 'required|string|max:255',
        //     'make_id' => 'required|integer|max:255',
        //     'model_id' => 'required|integer|max:255',
        //     'body_type_id' => 'required|integer|max:255',
        //     'drive_type_id' => 'required|integer|max:255',
        //     'transmission_id' => 'required|integer|max:255',
        //     'condition_id' => 'required|integer|max:255',
        //     'year' => 'required|integer|max:255',
        //     'fuel_type_id' => 'required|integer|max:255',
        //     'engine_size' => 'required|string|max:255',
        //     'door_id' => 'required|integer|max:255',
        //     'cylinder_id' => 'required|integer|max:255',
        //     'color_id' => 'required|integer|max:255',
        //     'description' => 'required|string|max:255',
        //     'price' => 'required|float|max:255',
        //     'safety_features' => 'required|string|max:255',
        //     'key_features' => 'required|string|max:255',
        //     'category_id' => 'required|integer|max:255',
        //     'imageURL' => 'required|string|max:255',
        //     'status' => 'required|integer|in:0,1',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        $carlist = Carlist::create([
            'car_id' => $request->sellecar_idr_id,
            'vin' => $request->vin,
            'heading' => $request->heading,
            'price' => $request->price,
            'miles' => $request->miles,
            'msrp' => $request->msrp,
            'data_source' => $request->data_source,
            'vdp_url' => $request->vdp_url,
            'carfax_1_owner' => $request->carfax_1_owner,
            'carfax_clean_title' => $request->carfax_clean_title,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'base_int_color' => $request->base_int_color,
            'base_ext_color' => $request->base_ext_color,
            'dom' => $request->dom,
            'dom_180' => $request->dom_180,
            'dom_active' => $request->dom_active,
            'dos_active' => $request->dos_active,
            'seller_type' => $request->seller_type,
            'inventory_type' => $request->inventory_type,
            'stock_no' => $request->stock_no,
            'last_seen_at' => $request->last_seen_at,
            'last_seen_at_date' => $request->last_seen_at_date,
            'scraped_at' => $request->scraped_at,
            'scraped_at_date' => $request->scraped_at_date,
            'first_seen_at' => $request->first_seen_at,
            'first_seen_at_date' => $request->first_seen_at_date,
            'first_seen_at_source' => $request->first_seen_at_source,
            'first_seen_at_source_date' => $request->first_seen_at_source_date,
            'first_seen_at_mc' => $request->first_seen_at_mc,
            'first_seen_at_mc_date' => $request->first_seen_at_mc_date,
            'ref_price' => $request->ref_price,
            'price_change_percent' => $request->price_change_percent,
            'ref_price_dt' => $request->ref_price_dt,
            'ref_miles' => $request->ref_miles,
            'ref_miles_dt' => $request->ref_miles_dt,
            'source' => $request->source,
            'model_code' => $request->model_code,
            'in_transit' => $request->in_transit,
            'photo_links' => $request->photo_links,
            'dealer_id' => $request->dealer_id,
            'year' => $request->year,
            'make' => $request->make,
            'model' => $request->model,
            'trim' => $request->trim,
            'version' => $request->version,
            'body_type' => $request->body_type,
            'body_subtype' => $request->body_subtype,
            'vehicle_type' => $request->vehicle_type,
            'transmission' => $request->transmission,
            'drivetrain' => $request->drivetrain,
            'fuel_type' => $request->fuel_type,
            'engine' => $request->engine,
            'engine_size' => $request->engine_size,
            'engine_block' => $request->engine_block,
            'doors' => $request->doors,
            'cylinders' => $request->cylinders,
            'made_in' => $request->made_in,
            'overall_height' => $request->overall_height,
            'overall_length' => $request->overall_length,
            'overall_width' => $request->overall_width,
            'std_seating' => $request->std_seating,
            'highway_mpg' => $request->highway_mpg,
            'city_mpg' => $request->city_mpg,
            'powertrain_type' => $request->powertrain_type,
        ]);

        return response()->json([
            'message' => 'New List Added Successfully',
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

        //Find product by ID
        $car_list = Carlist::find($id);

        if($car_list)
        {
            $make = Make::find($car_list->make_id);
            $model = Carmodel::find($car_list->model_id);
            $body_type = Body_Type::find($car_list->body_type_id);
            $drive_type = Drive_type::find($car_list->drive_type_id);
            $transmission = Transmission::find($car_list->transmission_id);
            $condition = Condition::find($car_list->condition_id);
            $fuel_type = Fuel_type::find($car_list->fuel_type_id);
            $door = Door::find($car_list->door_id);
            $cylinder = Cylinder::find($car_list->cylinder_id);
            $color = Color::find($car_list->color_id);
            $category = Category::find($car_list->category_id);
        }

        // Check if product exists
        if (!$car_list) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Car data retrieved successfully',
            'data' => [
                'car'=>$car_list,
                'make'=>$make,
                'model'=>$model,
                'body_type'=>$body_type,
                'drive_type'=>$drive_type,
                'transmission'=>$transmission,
                'condition'=>$condition,
                'fuel_type'=>$fuel_type,
                'door'=>$door,
                'cylinder'=>$cylinder,
                'color'=>$color,
                'category'=>$category,
            ],
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|string|max:255',
        //     'make_id' => 'required|integer|max:255',
        //     'model_id' => 'required|integer|max:255',
        //     'body_type_id' => 'required|integer|max:255',
        //     'drive_type_id' => 'required|integer|max:255',
        //     'transmission_id' => 'required|integer|max:255',
        //     'condition_id' => 'required|integer|max:255',
        //     'year' => 'required|integer|max:255',
        //     'fuel_type_id' => 'required|integer|max:255',
        //     'engine_size' => 'required|string|max:255',
        //     'door_id' => 'required|integer|max:255',
        //     'cylinder_id' => 'required|integer|max:255',
        //     'color_id' => 'required|integer|max:255',
        //     'description' => 'required|string|max:255',
        //     'price' => 'required|float|max:255',
        //     'safety_features' => 'required|string|max:255',
        //     'key_features' => 'required|string|max:255',
        //     'category_id' => 'required|integer|max:255',
        //     'imageURL' => 'required|string|max:255',
        //     'status' => 'required|integer|in:0,1',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // Find the carlist record
        $carlist = Carlist::find($id);

        if (!$carlist) {
            return response()->json(['message' => 'Car List Not Found'], 404);
        }

        // Update the record
        $carlist->update([
            'car_id' => $request->sellecar_idr_id,
            'vin' => $request->vin,
            'heading' => $request->heading,
            'price' => $request->price,
            'miles' => $request->miles,
            'msrp' => $request->msrp,
            'data_source' => $request->data_source,
            'vdp_url' => $request->vdp_url,
            'carfax_1_owner' => $request->carfax_1_owner,
            'carfax_clean_title' => $request->carfax_clean_title,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'base_int_color' => $request->base_int_color,
            'base_ext_color' => $request->base_ext_color,
            'dom' => $request->dom,
            'dom_180' => $request->dom_180,
            'dom_active' => $request->dom_active,
            'dos_active' => $request->dos_active,
            'seller_type' => $request->seller_type,
            'inventory_type' => $request->inventory_type,
            'stock_no' => $request->stock_no,
            'last_seen_at' => $request->last_seen_at,
            'last_seen_at_date' => $request->last_seen_at_date,
            'scraped_at' => $request->scraped_at,
            'scraped_at_date' => $request->scraped_at_date,
            'first_seen_at' => $request->first_seen_at,
            'first_seen_at_date' => $request->first_seen_at_date,
            'first_seen_at_source' => $request->first_seen_at_source,
            'first_seen_at_source_date' => $request->first_seen_at_source_date,
            'first_seen_at_mc' => $request->first_seen_at_mc,
            'first_seen_at_mc_date' => $request->first_seen_at_mc_date,
            'ref_price' => $request->ref_price,
            'price_change_percent' => $request->price_change_percent,
            'ref_price_dt' => $request->ref_price_dt,
            'ref_miles' => $request->ref_miles,
            'ref_miles_dt' => $request->ref_miles_dt,
            'source' => $request->source,
            'model_code' => $request->model_code,
            'in_transit' => $request->in_transit,
            'photo_links' => $request->photo_links,
            'dealer_id' => $request->dealer_id,
            'year' => $request->year,
            'make' => $request->make,
            'model' => $request->model,
            'trim' => $request->trim,
            'version' => $request->version,
            'body_type' => $request->body_type,
            'body_subtype' => $request->body_subtype,
            'vehicle_type' => $request->vehicle_type,
            'transmission' => $request->transmission,
            'drivetrain' => $request->drivetrain,
            'fuel_type' => $request->fuel_type,
            'engine' => $request->engine,
            'engine_size' => $request->engine_size,
            'engine_block' => $request->engine_block,
            'doors' => $request->doors,
            'cylinders' => $request->cylinders,
            'made_in' => $request->made_in,
            'overall_height' => $request->overall_height,
            'overall_length' => $request->overall_length,
            'overall_width' => $request->overall_width,
            'std_seating' => $request->std_seating,
            'highway_mpg' => $request->highway_mpg,
            'city_mpg' => $request->city_mpg,
            'powertrain_type' => $request->powertrain_type,
        ]);

        // Return success response
        return response()->json([
            'message' => 'List Updated Successfully',
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
