<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
use Modules\Auth\Models\Auth;
use Modules\Admin\Year\Models\Year;

class CarListAutoController extends Controller
{
    public function marketCheck()
    {


        // Fetch From API 1
        $apiKey = env('marketCheck'); // Replace with your API key
        // $apiKey = 'KHOUDaRN4thXldtn7PMMhtrsXJASlh1y'; // Replace with your API key
        $baseUrl = "https://mc-api.marketcheck.com/v2/search/car/active";
        $start = 0;
        $rows = 25; // Number of records per page
        $totalFetched = 0;
        

        

        try {
            while(true){
            // $currentPage =1;
            $url = "{$baseUrl}?api_key={$apiKey}&start={$start}&rows={$rows}";

            // Fetch data from the API
            // do{
                $response = Http::timeout(300)->get($url);
            // dd($response);
            Log::info($response);

            if ($response->successful()) {
                $data = $response->json();

                // dd($data);

                // Check if data is valid
                if (isset($data['listings']) && is_array($data['listings'])) {
                    foreach ($data['listings'] as $car) {
                        $existingCar = Carlist::where('car_id', $car['id'] ?? null)->orWhere('vin', $car['vin'] ?? null)->first();

                        // Fetch or create `ExteriorColor`
                        $exterior_colorData = null;
                        if (!empty($car['exterior_color'])) {
                            $exterior_color = ExteriorColor::firstOrCreate(
                                ['name' => $car['exterior_color']]
                            );
                            $exterior_colorData = $exterior_color->id;
                        }

                        // Fetch or create `InteriorColor`
                        $interior_colorData = null;
                        if (!empty($car['interior_color'])) {
                            $interior_color = InteriorColor::firstOrCreate(
                                ['name' => $car['interior_color']]
                            );
                            $interior_colorData = $interior_color->id;
                        }


                        // Fetch or create `InventoryType`
                        $inventory_typeData = null;
                        if (!empty($car['inventory_type'])) {
                            $inventory_type = InventoryType::firstOrCreate(
                                ['name' => $car['inventory_type']]
                            );
                            $inventory_typeData = $inventory_type->id;
                        }


                        // Fetch or create `SellerType`
                        $seller_typeData = null;
                        if (!empty($car['seller_type'])) {
                            $seller_type = SellerType::firstOrCreate(
                                ['name' => $car['seller_type']]
                            );
                            $seller_typeData = $seller_type->id;
                        }


                        // Fetch or create `Year`
                        $yearData = null;
                        if (!empty($car['build']['year'])) {
                            $year = Year::firstOrCreate(
                                ['name' => $car['build']['year']]
                            );
                            $yearData = $year->id;
                        }


                        // Fetch or create `Make`
                        $makeData = null;
                        if (!empty($car['build']['make'])) {
                            $make = Make::firstOrCreate(
                                ['name' => $car['build']['make']]
                            );
                            $makeData = $make->id;
                        }

                        // Fetch or create `Model`
                        $modelData = null;
                        if (!empty($car['build']['model'])) {
                            $model = Carmodel::firstOrCreate(
                                ['name' => $car['build']['model']]
                            );
                            $modelData = $model->id;
                        }
                        

                        // Fetch or create `Trim`
                        $trimData = null;
                        if (!empty($car['build']['trim'])) {
                            $trim = Trim::firstOrCreate(
                                ['name' => $car['build']['trim']]
                            );
                            $trimData = $trim->id;
                        }


                        // Fetch or create `Version`
                        $versionData = null;
                        if (!empty($car['build']['version'])) {
                            $version = Version::firstOrCreate(
                                ['name' => $car['build']['version']]
                            );
                            $versionData = $version->id;
                        }


                        // Fetch or create `Body_type`
                        $body_typeData = null;
                        if (!empty($car['build']['body_type'])) {
                            $body_type = Body_Type::firstOrCreate(
                                ['name' => $car['build']['body_type']]
                            );
                            $body_typeData = $body_type->id;
                        }


                        // Fetch or create `Body_subtype`
                        $body_subtypeData = null;
                        if (!empty($car['build']['body_subtype'])) {
                            $body_subtype = BodySubType::firstOrCreate(
                                ['name' => $car['build']['body_subtype']]
                            );
                            $body_subtypeData = $body_subtype->id;
                        }


                        // Fetch or create `Vehicle_type`
                        $vehicle_typeData = null;
                        if (!empty($car['build']['vehicle_type'])) {
                            $vehicle_type = VehicleType::firstOrCreate(
                                ['name' => $car['build']['vehicle_type']]
                            );
                            $vehicle_typeData = $vehicle_type->id;
                        }


                        // Fetch or create `Transmission`
                        $transmissionData = null;
                        if (!empty($car['build']['transmission'])) {
                            $transmission = Transmission::firstOrCreate(
                                ['name' => $car['build']['transmission']]
                            );
                            $transmissionData = $transmission->id;
                        }


                        // Fetch or create `Drivetrain`
                        $drivetrainData = null;
                        if (!empty($car['build']['drivetrain'])) {
                            $drivetrain = DriveTrain::firstOrCreate(
                                ['name' => $car['build']['drivetrain']]
                            );
                            $drivetrainData = $drivetrain->id;
                        }


                        // Fetch or create `Fuel_type`
                        $fuel_typeData = null;
                        if (!empty($car['build']['fuel_type'])) {
                            $fuel_type = Fuel_type::firstOrCreate(
                                ['name' => $car['build']['fuel_type']]
                            );
                            $fuel_typeData = $fuel_type->id;
                        }


                        // Fetch or create `Engine`
                        $engineData = null;
                        if (!empty($car['build']['engine'])) {
                            $engine = Engine::firstOrCreate(
                                ['name' => $car['build']['engine']]
                            );
                            $engineData = $engine->id;
                        }


                        // Fetch or create `Engine_size`
                        $engine_sizeData = null;
                        if (!empty($car['build']['engine_size'])) {
                            $engine_size = EngineSize::firstOrCreate(
                                ['name' => $car['build']['engine_size']]
                            );
                            $engine_sizeData = $engine_size->id;
                        }


                        // Fetch or create `Engine_block`
                        $engine_blockData = null;
                        if (!empty($car['build']['engine_block'])) {
                            $engine_block = EngineBlock::firstOrCreate(
                                ['name' => $car['build']['engine_block']]
                            );
                            $engine_blockData = $engine_block->id;
                        }


                        // Fetch or create `Doors`
                        $doorsData = null;
                        if (!empty($car['build']['doors'])) {
                            $doors = Door::firstOrCreate(
                                ['name' => $car['build']['doors']]
                            );
                            $doorsData = $doors->id;
                        }

                        // Fetch or create `Cylinders`
                        $cylindersData = null;
                        if (!empty($car['build']['cylinders'])) {
                            $cylinders = Cylinder::firstOrCreate(
                                ['name' => $car['build']['cylinders']]
                            );
                            $cylindersData = $cylinders->id;
                        }


                        // Fetch or create `Made_in`
                        $made_inData = null;
                        if (!empty($car['build']['made_in'])) {
                            $made_in = MadeIn::firstOrCreate(
                                ['name' => $car['build']['made_in']]
                            );
                            $made_inData = $made_in->id;
                        }

                        // Fetch or create `Overall_height`
                        $overall_heightData = null;
                        if (!empty($car['build']['overall_height'])) {
                            $overall_height = OverallHeight::firstOrCreate(
                                ['name' => $car['build']['overall_height']]
                            );
                            $overall_heightData = $overall_height->id;
                        }


                        // Fetch or create `Overall_length`
                        $overall_lengthData = null;
                        if (!empty($car['build']['overall_length'])) {
                            $overall_length = OverallLength::firstOrCreate(
                                ['name' => $car['build']['overall_length']]
                            );
                            $overall_lengthData = $overall_length->id;
                        }


                        // Fetch or create `Overall_width`
                        $overall_widthData = null;
                        if (!empty($car['build']['overall_width'])) {
                            $overall_width = OverallWidth::firstOrCreate(
                                ['name' => $car['build']['overall_width']]
                            );
                            $overall_widthData = $overall_width->id;
                        }


                        // Fetch or create `Std_seating`
                        $std_seatingData = null;
                        if (!empty($car['build']['std_seating'])) {
                            $std_seating = StdSeating::firstOrCreate(
                                ['name' => $car['build']['std_seating']]
                            );
                            $std_seatingData = $std_seating->id;
                        }


                        // Fetch or create `Highway_mpg`
                        $highway_mpgData = null;
                        if (!empty($car['build']['highway_mpg'])) {
                            $highway_mpg = HighwayMpg::firstOrCreate(
                                ['name' => $car['build']['highway_mpg']]
                            );
                            $highway_mpgData = $highway_mpg->id;
                        }


                        // Fetch or create `City_mpg`
                        $city_mpgData = null;
                        if (!empty($car['build']['city_mpg'])) {
                            $city_mpg = CityMpg::firstOrCreate(
                                ['name' => $car['build']['city_mpg']]
                            );
                            $city_mpgData = $city_mpg->id;
                        }


                        // Fetch or create `Powertrain_type`
                        $powertrain_typeData = null;
                        if (!empty($car['build']['powertrain_type'])) {
                            $powertrain_type = PowertrainType::firstOrCreate(
                                ['name' => $car['build']['powertrain_type']]
                            );
                            $powertrain_typeData = $powertrain_type->id;
                        }

                        // Fetch Dealer ID
                        // $dealer_id = FacadesAuth::id();
                        // if($car['dealer_id'] === $dealer_id)
                        // {
                        //     $user_id = Auth::where('dealer_id', $dealer_id)->first();
                        //     $modDealerId = $user_id->id;
                        // }

                        




                        if(!$existingCar)
                        {
                            Carlist::Create(
                                ['car_id'=>$car['id'],
                                            'vin'=>$car['vin'],
                                            'heading'=>$car['heading']??null,
                                            'price'=>$car['price']??null,
                                            'miles'=>$car['miles']??null,
                                            'msrp'=>$car['msrp']??null,
                                            'vdp_url'=>$car['vdp_url']??null,
                                            'carfax_1_owner'=>$car['carfax_1_owner']??null,
                                            'carfax_clean_title'=>$car['carfax_clean_title']??null,
                                            'exterior_color'=>$exterior_colorData??null,
                                            'interior_color'=>$interior_colorData??null,
                                            'base_int_color'=>$car['base_int_color']??null,
                                            'base_ext_color'=>$car['base_ext_color']??null,
                                            'dom'=>$car['dom']??null,
                                            'dom_180'=>$car['dom_180']??null,
                                            'dom_active'=>$car['dom_active']??null,
                                            'dos_active'=>$car['dos_active']??null,
                                            'seller_type'=>$seller_type??null,
                                            'inventory_type'=>$inventory_type??null,
                                            'stock_no'=>$car['stock_no']??null,
                                            'last_seen_at'=>$car['last_seen_at']??null,
                                            'last_seen_at_date'=>$car['last_seen_at_date']??null,
                                            'scraped_at'=>$car['scraped_at']??null,
                                            'scraped_at_date'=>$car['scraped_at_date']??null,
                                            'first_seen_at'=>$car['first_seen_at']??null,
                                            'first_seen_at_date'=>$car['first_seen_at_date']??null,
                                            'first_seen_at_source'=>$car['first_seen_at_source']??null,
                                            'first_seen_at_source_date'=>$car['first_seen_at_source_date']??null,
                                            'first_seen_at_mc'=>$car['first_seen_at_mc']??null,
                                            'first_seen_at_mc_date'=>$car['first_seen_at_mc_date']??null,
                                            'ref_price'=>$car['ref_price']??null,
                                            'price_change_percent'=>$car['price_change_percent']??null,
                                            'ref_price_dt'=>$car['ref_price_dt']??null,
                                            'ref_miles'=>$car['ref_miles']??null,
                                            'ref_miles_dt'=>$car['ref_miles_dt']??null,
                                            'source'=>$car['source']??null,
                                            'in_transit'=>$car['in_transit']??null,
                                            'photo_links'=>isset($car['media']['photo_links']) ? implode(',', $car['media']['photo_links']) : null,
                                            // 'photo_links'=>$car['media']['photo_links'],
                                            'dealer_id'=>$car['dealer']['id'],
                                            'year'=>$yearData??null,
                                            'make'=>$makeData??null,
                                            'model'=>$modelData??null,
                                            'trim'=>$trimData??null,
                                            'version'=>$versionData??null,
                                            'body_type'=>$body_typeData??null,
                                            'body_subtype'=>$body_subtypeData??null,
                                            'vehicle_type'=>$vehicle_typeData??null,
                                            'transmission'=>$transmissionData??null,
                                            'drivetrain'=>$drivetrainData??null,
                                            'fuel_type'=>$fuel_typeData??null,
                                            'engine'=>$engineData??null,
                                            'engine_size'=>$engine_sizeData??null,
                                            'engine_block'=>$engine_blockData??null,
                                            'doors'=>$doorsData??null,
                                            'cylinders'=>$cylindersData??null,
                                            'made_in'=>$made_inData??null,
                                            'overall_height'=>$overall_heightData??null,
                                            'overall_length'=>$overall_lengthData??null,
                                            'overall_width'=>$overall_widthData??null,
                                            'std_seating'=>$std_seatingData??null,
                                            'highway_mpg'=>$highway_mpgData??null,
                                            'city_mpg'=>$city_mpgData??null,
                                            'powertrain_type'=>$powertrain_typeData??null,
                                        ]);
                        }
                    }
                    $start+=25;
                }
                    // Update pagination variables
                    // $totalFetched += count($data['listings']);
                    // $start += $rows;
                    return response()->json(['message' => " Data Stored Successfully."]);
            }
            
            } 
            // }while (isset($data['listings']) && count($data['listings']) > 0);
            // return response()->json(['message' => "Fetched and stored  records successfully."]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

    }

    public function autoDev()
    {


        // Fetch From API 1
        $apiKey = env('autoDev'); // Replace with your API key
        // $apiKey = 'KHOUDaRN4thXldtn7PMMhtrsXJASlh1y'; // Replace with your API key
        $baseUrl = "https://auto.dev/api/listings?apikey={$apiKey}";
        $start = 0;
        $rows = 25; // Number of records per page
        $totalFetched = 0;
        

        

        try {
            while(true){
            // $currentPage =1;
            $url = "{$baseUrl}?api_key={$apiKey}";

            // Fetch data from the API
            // do{
                $response = Http::timeout(300)->get($url);
            // dd($response);
            // Log::info($response);

            if ($response->successful()) {
                $data = $response->json();

                // dd($data);

                // Check if data is valid
                if (isset($data['records']) && is_array($data['records'])) {
                    foreach ($data['records'] as $car) {
                        $existingCar = Carlist::where('car_id', $car['id'] ?? null)->orWhere('vin', $car['vin'] ?? null)->first();

                        // Fetch or create `ExteriorColor`
                        $exterior_colorData = null;
                        if (!empty($car['displayColor'])) {
                            $exterior_color = ExteriorColor::firstOrCreate(
                                ['name' => $car['displayColor']]
                            );
                            $exterior_colorData = $exterior_color->id;
                        }


                        // Fetch or create `InventoryType`
                        $inventory_typeData = null;
                        if (!empty($car['condition'])) {
                            $inventory_type = InventoryType::firstOrCreate(
                                ['name' => $car['condition']]
                            );
                            $inventory_typeData = $inventory_type->id;
                        }


                        // Fetch or create `SellerType`
                        $seller_typeData = null;
                        if (!empty($car['partnerType'])) {
                            $seller_type = SellerType::firstOrCreate(
                                ['name' => $car['partnerType']]
                            );
                            $seller_typeData = $seller_type->id;
                        }


                        // Fetch or create `Year`
                        $yearData = null;
                        if (!empty($car['year'])) {
                            $year = Year::firstOrCreate(
                                ['name' => $car['year']]
                            );
                            $yearData = $year->id;
                        }


                        // Fetch or create `Make`
                        $makeData = null;
                        if (!empty($car['make'])) {
                            $make = Make::firstOrCreate(
                                ['name' => $car['make']]
                            );
                            $makeData = $make->id;
                        }

                        // Fetch or create `Model`
                        $modelData = null;
                        if (!empty($car['model'])) {
                            $model = Carmodel::firstOrCreate(
                                ['name' => $car['model']]
                            );
                            $modelData = $model->id;
                        }
                        

                        // Fetch or create `Trim`
                        $trimData = null;
                        if (!empty($car['trim'])) {
                            $trim = Trim::firstOrCreate(
                                ['name' => $car['trim']]
                            );
                            $trimData = $trim->id;
                        }



                        // Fetch or create `Body_type`
                        $body_typeData = null;
                        if (!empty($car['bodyType'])) {
                            $body_type = Body_Type::firstOrCreate(
                                ['name' => $car['bodyType']]
                            );
                            $body_typeData = $body_type->id;
                        }


                        // Fetch or create `Body_subtype`
                        $body_subtypeData = null;
                        if (!empty($car['bodyStyle'])) {
                            $body_subtype = BodySubType::firstOrCreate(
                                ['name' => $car['bodyStyle']]
                            );
                            $body_subtypeData = $body_subtype->id;
                        }


                        




                        if(!$existingCar)
                        {
                            Carlist::Create(
                                ['car_id'=>$car['id'],
                                            'vin'=>$car['vin'],
                                            'heading'=>$car['make'].$car['model'].$car['year'],
                                            // 'price'=>$car['price']??null,
                                            'price' => isset($car['price']) ? (int) filter_var($car['price'], FILTER_SANITIZE_NUMBER_INT) : null,
                                            'miles'=>$car['milease']??null,
                                            'msrp'=>$car['msrp']??null,
                                            'vdp_url'=>$car['vdp_url']??null,
                                            'carfax_1_owner'=>$car['carfax_1_owner']??null,
                                            'carfax_clean_title'=>$car['carfax_clean_title']??null,
                                            'exterior_color'=>$exterior_colorData??null,
                                            'interior_color'=>$interior_colorData??null,
                                            'base_int_color'=>$car['base_int_color']??null,
                                            'base_ext_color'=>$car['base_ext_color']??null,
                                            'dom'=>$car['dom']??null,
                                            'dom_180'=>$car['dom_180']??null,
                                            'dom_active'=>$car['dom_active']??null,
                                            'dos_active'=>$car['dos_active']??null,
                                            'seller_type'=>$seller_type??null,
                                            'inventory_type'=>$inventory_type??null,
                                            'stock_no'=>$car['stock_no']??null,
                                            'last_seen_at'=>$car['last_seen_at']??null,
                                            'last_seen_at_date'=>$car['last_seen_at_date']??null,
                                            'scraped_at'=>$car['scraped_at']??null,
                                            'scraped_at_date'=>$car['scraped_at_date']??null,
                                            'first_seen_at'=>$car['first_seen_at']??null,
                                            'first_seen_at_date'=>$car['first_seen_at_date']??null,
                                            'first_seen_at_source'=>$car['first_seen_at_source']??null,
                                            'first_seen_at_source_date'=>$car['first_seen_at_source_date']??null,
                                            'first_seen_at_mc'=>$car['first_seen_at_mc']??null,
                                            'first_seen_at_mc_date'=>$car['first_seen_at_mc_date']??null,
                                            'ref_price'=>$car['ref_price']??null,
                                            'price_change_percent'=>$car['price_change_percent']??null,
                                            'ref_price_dt'=>$car['ref_price_dt']??null,
                                            'ref_miles'=>$car['ref_miles']??null,
                                            'ref_miles_dt'=>$car['ref_miles_dt']??null,
                                            'source'=>$car['source']??null,
                                            'in_transit'=>$car['in_transit']??null,
                                            // 'photo_links'=>isset($car['photoUrls']) ? implode(',', $car['photoUrls']) : null,
                                            'photo_links' => isset($car['photoUrls']) && is_array($car['photoUrls']) 
                                                            ? implode(',', array_slice($car['photoUrls'], 1)) 
                                                            : null,

                                            // 'photo_links'=>$car['media']['photo_links'],
                                            // 'dealer_id'=>$car['dealer']['id'],
                                            'year'=>$yearData??null,
                                            'make'=>$makeData??null,
                                            'model'=>$modelData??null,
                                            'trim'=>$trimData??null,
                                            'version'=>$versionData??null,
                                            'body_type'=>$body_typeData??null,
                                            'body_subtype'=>$body_subtypeData??null,
                                            'vehicle_type'=>$vehicle_typeData??null,
                                            'transmission'=>$transmissionData??null,
                                            'drivetrain'=>$drivetrainData??null,
                                            'fuel_type'=>$fuel_typeData??null,
                                            'engine'=>$engineData??null,
                                            'engine_size'=>$engine_sizeData??null,
                                            'engine_block'=>$engine_blockData??null,
                                            'doors'=>$doorsData??null,
                                            'cylinders'=>$cylindersData??null,
                                            'made_in'=>$made_inData??null,
                                            'overall_height'=>$overall_heightData??null,
                                            'overall_length'=>$overall_lengthData??null,
                                            'overall_width'=>$overall_widthData??null,
                                            'std_seating'=>$std_seatingData??null,
                                            'highway_mpg'=>$highway_mpgData??null,
                                            'city_mpg'=>$city_mpgData??null,
                                            'powertrain_type'=>$powertrain_typeData??null,
                                        ]);
                        }
                    }
                    $start+=25;
                }
                    // Update pagination variables
                    // $totalFetched += count($data['listings']);
                    // $start += $rows;
                    return response()->json(['message' => " Data Stored Successfully."]);
            }
            
            } 
            // }while (isset($data['listings']) && count($data['listings']) > 0);
            // return response()->json(['message' => "Fetched and stored  records successfully."]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

    }
    
}



