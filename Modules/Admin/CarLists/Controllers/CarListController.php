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
        $validator = Validator::make($request->all(),[
            'car_id' => 'required|string',
            'vin' => 'required|string',
            'heading' => 'required|string',
            'price' => 'required|string',
            'miles' => 'nullable|string',
            'msrp' => 'nullable|string',
            'data_source' => 'nullable|string',
            'vdp_url' => 'nullable|string',
            'carfax_1_owner' => 'nullable|string',
            'carfax_clean_title' => 'nullable|string',
            'exterior_color' => 'nullable|string',
            'interior_color' => 'nullable|string',
            'base_int_color' => 'nullable|string',
            'base_ext_color' => 'nullable|string',
            'dom' => 'nullable|string',
            'dom_180' => 'nullable|string',
            'dom_active' => 'nullable|string',
            'dos_active' => 'nullable|string',
            'seller_type' => 'nullable|string',
            'inventory_type' => 'nullable|string',
            'stock_no' => 'nullable|string',
            'last_seen_at' => 'nullable|string',
            'last_seen_at_date' => 'nullable|string',
            'scraped_at' => 'nullable|string',
            'scraped_at_date' => 'nullable|string',
            'first_seen_at' => 'nullable|string',
            'first_seen_at_date' => 'nullable|string',
            'first_seen_at_source' => 'nullable|string',
            'first_seen_at_source_date' => 'nullable|string',
            'first_seen_at_mc' => 'nullable|string',
            'first_seen_at_mc_date' => 'nullable|string',
            'ref_price' => 'nullable|string',
            'price_change_percent' => 'nullable|string',
            'ref_price_dt' => 'nullable|string',
            'ref_miles' => 'nullable|string',
            'ref_miles_dt' => 'nullable|string',
            'source' => 'nullable|string',
            'model_code' => 'nullable|string',
            'in_transit' => 'nullable|string',
            'photo_links' => 'required|array',
            'photo_links.*' =>'image|mimes:jpeg,png,jpg,gif|max:2048',
            'dealer_id' => 'nullable|string',
            'year' => 'nullable|string',
            'make' => 'nullable|string',
            'model' => 'nullable|string',
            'trim' => 'nullable|string',
            'version' => 'nullable|string',
            'body_type' => 'nullable|string',
            'body_subtype' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'transmission' => 'nullable|string',
            'drivetrain' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'engine_block' => 'nullable|string',
            'doors' => 'nullable|string',
            'cylinders' => 'nullable|string',
            'made_in' => 'nullable|string',
            'overall_height' => 'nullable|string',
            'overall_length' => 'nullable|string',
            'overall_width' => 'nullable|string',
            'std_seating' => 'nullable|string',
            'highway_mpg' => 'nullable|string',
            'city_mpg' => 'nullable|string',
            'powertrain_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        

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

        $imagePaths = [];
        if ($request->hasFile('photo_links')) {
            foreach ($request->file('photo_links') as $image) {
                $path = $image->store('productImages', 'public'); // Store images in storage/app/public/products
                $imagePaths[] = asset(env('BASE_URL').'storage/' . $path);
            }
        }

        // Save image URLs to product
        $carlist->update([
            'image_urls' => implode(',', $imagePaths), // Store URLs as comma-separated values
        ]);

        return response()->json([
            'message' => 'New List Added Successfully',
            'data' => $carlist,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // Check if the request is for the first page
        if($request->page === '0'){
            $perPage =  Carlist::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }
        $qry = Carlist::query();

        // Apply Filters
        if ($request->filled('heading')) {
            $qry->where('heading', 'LIKE', '%' . $request->heading . '%');
        }
        if ($request->filled('year')) {
            $qry->whereHas('year', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->year . '%');
            });
        }
        if ($request->filled('make')) {
            $qry->whereHas('make', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->make . '%');
            });
        }
        if ($request->filled('model')) {
            $qry->whereHas('model', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->model . '%');
            });
        }

        // Apply Sorting with Joins
        if ($request->filled('sortField') && $request->filled('sortDirection')) {
            $sortField = $request->input('sortField');
            $sortDirection = $request->input('sortDirection') === 'asc' ? 'asc' : 'desc';
    
            // Define sortable fields and their corresponding joins
            $sortableFields = [
                'year' => ['table' => 'years', 'column' => 'name', 'foreign_key' => 'year'],
                'make' => ['table' => 'makes', 'column' => 'name', 'foreign_key' => 'make'],
                'model' => ['table' => 'carmodels', 'column' => 'name', 'foreign_key' => 'model'],
                'price' => ['table' => 'carlists', 'column' => 'price'],
                'createdAt' => ['table' => 'carlists', 'column' => 'created_at'],
            ];
    
            if (isset($sortableFields[$sortField])) {
                $field = $sortableFields[$sortField];
                if (isset($field['table']) && $field['table'] !== 'carlists') {
                    // Use LEFT JOIN instead of JOIN
                    $qry->leftJoin($field['table'], "{$field['table']}.id", '=', "carlists.{$field['foreign_key']}")
                        ->orderBy("{$field['table']}.{$field['column']}", $sortDirection);
                } else {
                    $qry->orderBy("carlists.{$field['column']}", $sortDirection);
                }
            }
            
        } else {
            $qry->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        // Include Relationships
        $qry->with(['make', 'model', 'year', 'body_type', 'fuel_type']);

        // Paginate Results
        $data = $qry->select('carlists.*')->groupBy('carlists.id')->paginate($perPage);

        // Response
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
            'data' => $data->items(),
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
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|string',
            'vin' => 'required|string',
            'heading' => 'required|string',
            'price' => 'required|string',
            'miles' => 'nullable|string',
            'msrp' => 'nullable|string',
            'data_source' => 'nullable|string',
            'vdp_url' => 'nullable|string',
            'carfax_1_owner' => 'nullable|string',
            'carfax_clean_title' => 'nullable|string',
            'exterior_color' => 'nullable|string',
            'interior_color' => 'nullable|string',
            'base_int_color' => 'nullable|string',
            'base_ext_color' => 'nullable|string',
            'dom' => 'nullable|string',
            'dom_180' => 'nullable|string',
            'dom_active' => 'nullable|string',
            'dos_active' => 'nullable|string',
            'seller_type' => 'nullable|string',
            'inventory_type' => 'nullable|string',
            'stock_no' => 'nullable|string',
            'last_seen_at' => 'nullable|string',
            'last_seen_at_date' => 'nullable|string',
            'scraped_at' => 'nullable|string',
            'scraped_at_date' => 'nullable|string',
            'first_seen_at' => 'nullable|string',
            'first_seen_at_date' => 'nullable|string',
            'first_seen_at_source' => 'nullable|string',
            'first_seen_at_source_date' => 'nullable|string',
            'first_seen_at_mc' => 'nullable|string',
            'first_seen_at_mc_date' => 'nullable|string',
            'ref_price' => 'nullable|string',
            'price_change_percent' => 'nullable|string',
            'ref_price_dt' => 'nullable|string',
            'ref_miles' => 'nullable|string',
            'ref_miles_dt' => 'nullable|string',
            'source' => 'nullable|string',
            'model_code' => 'nullable|string',
            'in_transit' => 'nullable|string',
            'photo_links' => 'nullable|string',
            'dealer_id' => 'nullable|string',
            'year' => 'nullable|string',
            'make' => 'nullable|string',
            'model' => 'nullable|string',
            'trim' => 'nullable|string',
            'version' => 'nullable|string',
            'body_type' => 'nullable|string',
            'body_subtype' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'transmission' => 'nullable|string',
            'drivetrain' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'engine_block' => 'nullable|string',
            'doors' => 'nullable|string',
            'cylinders' => 'nullable|string',
            'made_in' => 'nullable|string',
            'overall_height' => 'nullable|string',
            'overall_length' => 'nullable|string',
            'overall_width' => 'nullable|string',
            'std_seating' => 'nullable|string',
            'highway_mpg' => 'nullable|string',
            'city_mpg' => 'nullable|string',
            'powertrain_type' => 'nullable|string',
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
