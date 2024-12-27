<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\DriveTrain\Models\DriveTrain;
use Modules\Admin\Engine\Models\Engine;
use Modules\Admin\Engine_Block\Models\EngineBlock;
use Modules\Admin\Engine_Size\Models\EngineSize;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\Trim\Models\Trim;
use Modules\Admin\Vehicle_Type\Models\VehicleType;
use Modules\Admin\Version\Models\Version;
use Modules\Auth\Models\Auth;
use Modules\Admin\Year\Models\Year;

class CarListAutoController extends Controller
{
    public function index()
    {
        $apiKey = 'VB4mP3G5o2E0kKNFUuviGLNqe5Mxb5NE'; // Replace with your API key
        $url = "https://mc-api.marketcheck.com/v2/search/car/active?api_key={$apiKey}";

        try {
            // Fetch data from the API
            $response = Http::get($url);
            // dd($response);

            if ($response->successful()) {
                $data = $response->json();
                // $insert=Carlist::create($data);
                // // dd($data);
                // if($insert){
                //     return response([
                //         'message'=>'data fetch done',
                //     ], 200);
                // }

                // Check if data is valid
                if (isset($data['listings']) && is_array($data['listings'])) {
                    foreach ($data['listings'] as $car) {
                        $existingCar = Carlist::where('car_id', $car['id'] ?? null)->orWhere('vin', $car['vin'] ?? null)->first();


                        // Year
                        $year = Year::where('name', $car['build']['year'] ?? null)->first();
                        // dd($car);
                        if(!$year)
                        {
                            Year::Create([
                                'name'=>$car['build']['year'] ?? null,
                            ]);
                        }

                        $yearData = Year::where('name', $car['build']['year'])->value('id');

                        // Make
                        $car_make = Make::where('name', $car['build']['make'] ?? null)->first();
                        // dd($car);
                        if(!$car_make)
                        {
                            Make::Create([
                                'name'=>$car['build']['make'] ?? null,
                            ]);
                        }

                        $CarMakeData = Make::where('name', $car['build']['make'])->value('id');

                        // Model
                        $car_model = Carmodel::where('name', $car['build']['model'] ?? null)->first();
                        // dd($car);
                        if(!$car_model)
                        {
                            Carmodel::Create([
                                'name'=>$car['build']['model'] ?? null,
                            ]);
                        }

                        $CarModelData = Carmodel::where('name', $car['build']['model'])->value('id');


                        // Trim
                        $trim = Trim::where('name', $car['build']['trim'] ?? null)->first();
                        // dd($car);
                        if(!$trim)
                        {
                            Trim::Create([
                                'name'=>$car['build']['trim'] ?? null,
                            ]);
                        }

                        $TrimData = Trim::where('name', $car['build']['trim'])->value('id');


                        // Version
                        $version = Version::where('name', $car['build']['version'] ?? null)->first();
                        // dd($car);
                        if(!$version)
                        {
                            Version::Create([
                                'name'=>$car['build']['version'] ?? null,
                            ]);
                        }

                        $VersionData = Trim::where('name', $car['build']['version'])->value('id');


                        // Body Type
                        $body_type = Body_Type::where('name', $car['build']['body_type'] ?? null)->first();
                        // dd($car);
                        if(!$body_type)
                        {
                            Body_Type::Create([
                                'name'=>$car['build']['body_type'] ?? null,
                            ]);
                        }

                        $BodyTypeData = Body_Type::where('name', $car['build']['body_type'])->value('id');


                        // Body Sub Type
                        $body_subtype = BodySubType::where('name', $car['build']['body_subtype'] ?? null)->first();
                        // dd($body_subtype);
                        if(!$body_subtype)
                        {
                            BodySubType::Create([
                                'name'=>$car['build']['body_subtype'] ?? null,
                            ]);
                        }

                        $BodySubTypeData = BodySubType::where('name', $car['build']['body_subtype'])->value('id');


                        // Vehicle Type
                        $vehicle_type = VehicleType::where('name', $car['build']['vehicle_type'] ?? null)->first();
                        // dd($car);
                        if(!$vehicle_type)
                        {
                            VehicleType::Create([
                                'name'=>$car['build']['vehicle_type'] ?? null,
                            ]);
                        }

                        $VehicleTypeData = VehicleType::where('name', $car['build']['vehicle_type'])->value('id');


                        // Transmission
                        $transmission = Transmission::where('name', $car['build']['transmission'] ?? null)->first();
                        // dd($car);
                        if(!$transmission)
                        {
                            Transmission::Create([
                                'name'=>$car['build']['transmission'] ?? null,
                            ]);
                        }

                        $TransmissionData = Transmission::where('name', $car['build']['transmission'])->value('id');


                        // Drive Train
                        $drive_train = DriveTrain::where('name', $car['build']['drive_train'] ?? null)->first();
                        // dd($car);
                        if(!$drive_train)
                        {
                            DriveTrain::Create([
                                'name'=>$car['build']['drive_train'] ?? null,
                            ]);
                        }

                        $drivetrainData = DriveTrain::where('name', $car['build']['drive_train'])->value('id');


                        // Fuel Type
                        $fuel_type = Fuel_type::where('name', $car['build']['fuel_type'] ?? null)->first();
                        // dd($car);
                        if(!$fuel_type)
                        {
                            Fuel_type::Create([
                                'name'=>$car['build']['fuel_type'] ?? null,
                            ]);
                        }

                        $fueltypeData = Fuel_type::where('name', $car['build']['fuel_type'])->value('id');


                        // Engine
                        $engine = Engine::where('name', $car['build']['engine'] ?? null)->first();
                        // dd($car);
                        if(!$engine)
                        {
                            Engine::Create([
                                'name'=>$car['build']['engine'] ?? null,
                            ]);
                        }

                        $engineData = Engine::where('name', $car['build']['engine'])->value('id');


                        // Engine Size
                        $engine_size = EngineSize::where('name', $car['build']['engine_size'] ?? null)->first();
                        // dd($car);
                        if(!$engine_size)
                        {
                            EngineSize::Create([
                                'name'=>$car['build']['engine_size'] ?? null,
                            ]);
                        }

                        $enginesizeData = EngineSize::where('name', $car['build']['engine_size'])->value('id');


                        // Engine Block
                        $engine_block = EngineBlock::where('name', $car['build']['engine_block'] ?? null)->first();
                        // dd($car);
                        if(!$engine_block)
                        {
                            EngineBlock::Create([
                                'name'=>$car['build']['engine_block'] ?? null,
                            ]);
                        }

                        $engineblockData = EngineBlock::where('name', $car['build']['engine_block'])->value('id');


                        // Door
                        $door = Door::where('name', $car['build']['doors'] ?? null)->first();
                        // dd($car);
                        if(!$door)
                        {
                            Door::Create([
                                'name'=>$car['build']['doors'] ?? null,
                            ]);
                        }

                        $doorData = Door::where('name', $car['build']['door'])->value('id');
                        

                        // Cylinder
                        $cylinder = Cylinder::where('name', $car['build']['cylinders'] ?? null)->first();
                        // dd($car);
                        if(!$cylinder)
                        {
                            Cylinder::Create([
                                'name'=>$car['build']['cylinders'] ?? null,
                            ]);
                        }

                        $cylinderData = Door::where('name', $car['build']['cylinders'])->value('id');

                        

                        // Made In
                        $made_in = MadeIn::where('name', $car['build']['made_in'] ?? null)->first();
                        // dd($car);
                        if(!$made_in)
                        {
                            MadeIn::Create([
                                'name'=>$car['build']['made_in'] ?? null,
                            ]);
                        }

                        $madeinData = MadeIn::where('name', $car['build']['made_in'])->value('id');


                        // Overall Height
                        $overall_height = OverallHeight::where('name', $car['build']['overall_height'] ?? null)->first();
                        // dd($car);
                        if(!$overall_height)
                        {
                            OverallHeight::Create([
                                'name'=>$car['build']['overall_height'] ?? null,
                            ]);
                        }

                        $overallheightData = OverallHeight::where('name', $car['build']['overall_height'])->value('id');


                        // Overall Length
                        $overall_length = Overalllength::where('name', $car['build']['overall_length'] ?? null)->first();
                        // dd($car);
                        if(!$overall_length)
                        {
                            OverallLength::Create([
                                'name'=>$car['build']['overall_length'] ?? null,
                            ]);
                        }

                        $overalllengthData = OverallLength::where('name', $car['build']['overall_width'])->value('id');


                        // Overall Width
                        $overall_width = OverallWidth::where('name', $car['build']['overall_width'] ?? null)->first();
                        // dd($car);
                        if(!$overall_width)
                        {
                            OverallWidth::Create([
                                'name'=>$car['build']['overall_length'] ?? null,
                            ]);
                        }

                        $overallwidthData = OverallWidth::where('name', $car['build']['overall_width'])->value('id');


                        // Overall Width
                        $overall_width = OverallWidth::where('name', $car['build']['overall_width'] ?? null)->first();
                        // dd($car);
                        if(!$overall_width)
                        {
                            OverallWidth::Create([
                                'name'=>$car['build']['overall_length'] ?? null,
                            ]);
                        }

                        $overallwidthData = OverallWidth::where('name', $car['build']['overall_width'])->value('id');

                        




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
                                            'exterior_color'=>$car['exterior_color']??null,
                                            'interior_color'=>$car['interior_color']??null,
                                            'base_int_color'=>$car['base_int_color']??null,
                                            'base_ext_color'=>$car['base_ext_color']??null,
                                            'dom'=>$car['dom']??null,
                                            'dom_180'=>$car['dom_180']??null,
                                            'dom_active'=>$car['dom_active']??null,
                                            'dos_active'=>$car['dos_active']??null,
                                            'seller_type'=>$car['seller_type']??null,
                                            'inventory_type'=>$car['inventory_type']??null,
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
                                            'make'=>$CarMakeData??null,
                                            'model'=>$CarModelData??null,
                                            'trim'=>$car['build']['trim']??null,
                                            'version'=>$car['build']['version']??null,
                                            'body_type'=>$car['build']['body_type']??null,
                                            'body_subtype'=>$car['build']['body_subtype']??null,
                                            'vehicle_type'=>$car['build']['vehicle_type']??null,
                                            'transmission'=>$car['build']['transmission']??null,
                                            'drivetrain'=>$car['build']['drivetrain']??null,
                                            'fuel_type'=>$car['build']['fuel_type']??null,
                                            'engine'=>$car['build']['engine']??null,
                                            'engine_size'=>$car['build']['engine_size']??null,
                                            'engine_block'=>$car['build']['engine_block']??null,
                                            'doors'=>$car['build']['doors']??null,
                                            'cylinders'=>$car['build']['cylinders']??null,
                                            'made_in'=>$car['build']['made_in']??null,
                                            'overall_height'=>$car['build']['overall_height']??null,
                                            'overall_length'=>$car['build']['overall_length']??null,
                                            'overall_width'=>$car['build']['overall_width']??null,
                                            'std_seating'=>$car['build']['std_seating']??null,
                                            'highway_mpg'=>$car['build']['highway_mpg']??null,
                                            'city_mpg'=>$car['build']['city_mpg']??null,
                                            'powertrain_type'=>$car['build']['powertrain_type']??null,
                                        ]);
                        }
                    }

                    return response()->json(['message' => 'Data fetched and stored successfully.']);
                } else {
                    return response()->json(['message' => 'Invalid data format from API.'], 400);
                }
            } else {
                return response()->json(['message' => 'Failed to fetch data from API.'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function get_dealer(){
        $apiKey = 'VB4mP3G5o2E0kKNFUuviGLNqe5Mxb5NE'; // Replace with your API key
        $url = "https://mc-api.marketcheck.com/v2/dealers/car?api_key=$apiKey";

        try {
            // Fetch data from the API
            $response = Http::get($url);
            // dd($response);

            if ($response->successful()) {
                $data = $response->json();
                // $insert=VIN::create($data);
                // // dd($data);
                // if($insert){
                //     return response([
                //         'message'=>'data fetch done',
                //     ], 200);
                // }

                // Check if data is valid
                if (isset($data['dealers']) && is_array($data['dealers'])) {
                    foreach ($data['dealers'] as $dealer) {
                        
                        $existingDealer = Auth::where('email', $dealer['seller_email'] ?? null)->orWhere('phone', $dealer['seller_phone'] ?? null)->first();
                        if(!$existingDealer){
                        // dd($dealer['street'], $dealer['seller_phone'],$dealer['seller_name']);
                        Auth::Create([
                            'dealer_id' => $dealer['id'] ?? null,
                            'name' => $dealer['seller_name'] ?? null,
                            'email' => $dealer['seller_email'] ?? null, // Fixed typo here
                            'phone' => $dealer['seller_phone'] ?? null,
                            'street' => $dealer['street'] ?? null,
                            'state' => $dealer['state'] ?? null,
                            'city' => $dealer['city'] ?? null,
                            'zip' => $dealer['zip'] ?? null,
                            'country' => $dealer['country'] ?? null,
                            'inventory_url' => $dealer['inventory_url'] ?? null,
                            'data_source' => $dealer['data_source'] ?? null,
                            'listing_count' => $dealer['listing_count'] ?? null,
                            'latitude' => $dealer['latitude'] ?? null,
                            'longitude' => $dealer['longitude'] ?? null,
                            'status' => $dealer['status'] ?? null,
                            'dealer_type' => $dealer['dealer_type'] ?? null,
                            'created_at' => $dealer['created_at'] ?? null,
                        ]);
                    }
                    }
                    return response()->json(['message' => 'Data fetched and stored successfully.']);
                } else {
                    return response()->json(['message' => 'Invalid data format from API.'], 400);
                }
            } else {
                return response()->json(['message' => 'Failed to fetch data from API.'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

}
