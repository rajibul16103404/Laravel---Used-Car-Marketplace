<?php

namespace Modules\Admin\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

class UserCarListContrioller extends Controller
{
    // public function index(Request $request)
    // {
    //     $user_id = Auth::id();
    //     $perPage = $request->input('per_page', 10);

    //     $data = Carlist::where('dealer_id', $user_id)->orWhere('id', $user_id)->where('status','!=','sold')->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate($perPage);

    //     return response()->json([
    //         'pagination' => [
    //             'total_count'=>$data->total(),
    //             'total_page'=>$data->lastPage(),
    //             'current_page'=>$data->currentPage(),
    //             'current_page_count'=>$data->count(),
    //             'next_page' => $data->hasMorePages() ? $data->currentPage()+1 : null,
    //             'previous_page'=>$data->onFirstPage() ? null : $data->currentPage()
    //         ],
    //         'message' => 'Data Retrieved Successfully',
    //         'data' => $data->items(),
    //     ],200);
    // }



    public function index(Request $request)
    {
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
        $qry->with(['make', 'model', 'year', 'body_type', 'fuel_type'])->where('dealer_id', Auth::id());

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
}
