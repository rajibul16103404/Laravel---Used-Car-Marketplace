<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\City_Mpg\Models\CityMpg;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\DriveTrain\Models\DriveTrain;
use Modules\Admin\Engine\Models\Engine;
use Modules\Admin\Engine_Block\Models\EngineBlock;
use Modules\Admin\Engine_Size\Models\EngineSize;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\Highway_Mpg\Models\HighwayMpg;
use Modules\Admin\Inventory_Type\Models\InventoryType;
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;
use Modules\Admin\Seller_Type\Models\SellerType;
use Modules\Admin\Std_seating\Models\StdSeating;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\Trim\Models\Trim;
use Modules\Admin\Vehicle_Type\Models\VehicleType;
use Modules\Admin\Version\Models\Version;
use Modules\Admin\Year\Models\Year;

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
            'car_id' => $request->car_id,
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
        $perPage = $request->input('per_page', 10);

        $qry = Carlist::query()
        ->join('years','carlists.year','=','years.id')
        ->join('makes','carlists.make','=','makes.id')
        ->join('carmodels','carlists.model','=','carmodels.id');

        // if ($request->filled('')) {
        //     $qry->orderBy('carlists.created_at', 'desc');
        // }

        if($request->filled('heading')){
            $qry->where('carlists.heading', 'LIKE', '%' . $request->heading . '%');
        }

        if($request->filled('year')){
            $qry->where('years.name', 'LIKE', '%' . $request->year . '%');
        }
        
        if($request->filled(key: 'make')){
            $qry->where('makes.name', 'LIKE', '%' . $request->make . '%');
        }

        if($request->filled('model')){
            $qry->where('carmodels.name', 'LIKE', '%' . $request->model . '%');
        }

        if ($request->filled('sortField') && $request->input('sortField') === 'year' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
            $qry->orderBy('years.name', 'asc');
        }
        if ($request->filled('sortField') && $request->input('sortField') === 'year' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
            $qry->orderBy('years.name', 'desc');
        }

        if ($request->filled('sortField') && $request->input('sortField') === 'make' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
            $qry->orderBy('makes.name', 'asc');
        }
        if ($request->filled('sortField') && $request->input('sortField') === 'make' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
            $qry->orderBy('makes.name', 'desc');
        }

        if ($request->filled('sortField') && $request->input('sortField') === 'model' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
            $qry->orderBy('carmodels.name', 'asc');
        }
        if ($request->filled('sortField') && $request->input('sortField') === 'model' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
            $qry->orderBy('carmodels.name', 'desc');
        }

        if ($request->filled('sortField') && $request->input('sortField') === 'price' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
            $qry->orderBy('price', 'asc');
        }
        if ($request->filled('sortField') && $request->input('sortField') === 'price' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
            $qry->orderBy('price', 'desc');
        }

        if ($request->filled('sortField') && $request->input('sortField') === 'createdAt' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
            $qry->orderBy('created_at', 'asc')->orderBy('carlists.id', 'asc');
        }
        if ($request->filled('sortField') && $request->input('sortField') === 'createdAt' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
            $qry->orderBy('created_at', 'desc')->orderBy('carlists.id', 'desc');
        }

        // if ($request->filled('sortField') && $request->input('sortField') === 'year' && $request->filled('sortDirection') && $request->input('sortDirection') === 'asc') {
        //     $qry->orderBy('year', 'desc');
        // }
        // if ($request->filled('sortField') && $request->input('sortField') === 'year' && $request->filled('sortDirection') && $request->input('sortDirection') === 'desc') {
        //     $qry->orderBy('year', 'asc');
        // }
        
    
        // Get paginated data
        $data = $qry->select('carlists.*')->orderBy('carlists.created_at', 'desc')->orderBy('carlists.id', 'desc')->paginate($perPage);
    
        // Add the corresponding year data for each car
        $items = $data->items();
        foreach ($items as $item) {
            $item->year = Year::find($item->year);
            $item->body_type = Body_Type::find($item->body_type);
            $item->fuel_type = Fuel_type::find($item->fuel_type);
            $item->make = Make::find($item->make);
            $item->model = Carmodel::find($item->model);
        }
    
        // Prepare response
        return response()->json([
            'pagination' => [
                'total_count' => $data->total(),
                'total_page' => $data->lastPage(),
                'current_page' => $data->currentPage(),
                'current_page_count' => $data->count(),
                'next_page' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                'previous_page' => $data->onFirstPage() ? null : $data->currentPage(),
            ],
            'message' => 'Data Retrieved Successfully',
            'data' => $items, // This now contains the additional 'year' data
        ], 200);
    }
    

    public function show($id)
    {

        $cacheKey = "product_viewed_{$id}_" . request()->ip();
    
        if (!Cache::has($cacheKey)) {
            $product = Carlist::findOrFail($id);
            $product->increment('view_count');

            // Set a cache entry for 1 hour
            Cache::put($cacheKey, true, 1);
        } else {
            $product = Carlist::findOrFail($id);
        }

        //Find product by ID
        $car_list = Carlist::find($id);

        if($car_list)
        {
            $inventory_type = InventoryType::find($car_list->inventory_type);
            $seller_type = SellerType::find($car_list->seller_type);
            $year = Year::find($car_list->year);
            $exterior_color = ExteriorColor::find($car_list->exterior_color);
            $interior_color = InteriorColor::find($car_list->interior_color);
            $make = Make::find($car_list->make);
            $model = Carmodel::find($car_list->model);
            $trim = Trim::find($car_list->trim);
            $version = Version::find($car_list->version);
            $body_type = Body_Type::find($car_list->body_type);
            $body_subtype = BodySubType::find($car_list->body_subtype);
            $vehicle_type = VehicleType::find($car_list->vehicle_type);
            $transmission = Transmission::find($car_list->transmission);
            $drivetrain = DriveTrain::find($car_list->drivetrain);
            $fuel_type = Fuel_type::find($car_list->fuel_type);
            $engine = Engine::find($car_list->engine);
            $engine_size = EngineSize::find($car_list->engine_size);
            $engine_block = EngineBlock::find($car_list->engine_block);
            $doors = Door::find($car_list->doors);
            $cylinders = Cylinder::find($car_list->cylinders);
            $made_in = MadeIn::find($car_list->made_in);
            $overall_height = OverallHeight::find($car_list->overall_height);
            $overall_length = OverallLength::find($car_list->overall_length);
            $overall_width = OverallWidth::find($car_list->overall_width);
            $std_seating = StdSeating::find($car_list->std_seating);
            $highway_mpg = HighwayMpg::find($car_list->highway_mpg);
            $city_mpg = CityMpg::find($car_list->city_mpg);
            $powertrain_type = PowertrainType::find($car_list->powertrain_type);
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
                'inventory_type' => $inventory_type,
                'seller_type' => $seller_type,
                'exterior_color' => $exterior_color,
                'interior_color' => $interior_color,
                'year' => $year,
                'make' => $make,
                'model' => $model,
                'trim' => $trim,
                'version' => $version,
                'body_type' => $body_type,
                'body_subtype' => $body_subtype,
                'vehicle_type' => $vehicle_type,
                'transmission' => $transmission,
                'drivetrain' => $drivetrain,
                'fuel_type' => $fuel_type,
                'engine' => $engine,
                'engine_size' => $engine_size,
                'engine_block' => $engine_block,
                'doors' => $doors,
                'cylinders' => $cylinders,
                'made_in' => $made_in,
                'overall_height' => $overall_height,
                'overall_length' => $overall_length,
                'overall_width' => $overall_width,
                'std_seating' => $std_seating,
                'highway_mpg' => $highway_mpg,
                'city_mpg' => $city_mpg,
                'powertrain_type' => $powertrain_type,  
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
