<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;
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
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;
use Modules\Admin\Std_seating\Models\StdSeating;
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


        // Fetch From API 1
        // $apiKey = 'VB4mP3G5o2E0kKNFUuviGLNqe5Mxb5NE'; // Replace with your API key
        $apiKey = 'KHOUDaRN4thXldtn7PMMhtrsXJASlh1y'; // Replace with your API key
        $url = "https://mc-api.marketcheck.com/v2/search/car/active?api_key={$apiKey}&start=0&rows=25";

        


        

        try {
            // $currentPage =1;
            // $url = "https://mc-api.marketcheck.com/v2/search/car/active?api_key={$apiKey}";

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


                        // Exterior Color
                        if(in_array(['exterior_color'], $car)){
                            $exterior_color = ExteriorColor::where('name', $car['exterior_color'] ?? null)->first();
                            // dd($car['exterior_color']);
                            if(!$exterior_color)
                            {
                                ExteriorColor::Create([
                                    'name'=>$car['exterior_color'] ?? null,
                                ]);
                            }

                            $exterior_colorData = ExteriorColor::where('name', $car['exterior_color'])->value('id');
                        }else{
                            $exterior_colorData=null;
                        }

                        



                        // Interior Color
                        if(in_array(['interior_color'], $car)){
                            $interior_color = InteriorColor::where('name', $car['interior_color'] ?? null)->first();
                            // dd($car);
                            if(!$interior_color)
                            {
                                InteriorColor::Create([
                                    'name'=>$car['interior_color'] ?? null,
                                ]);
                            }

                            $interior_colorData = InteriorColor::where('name', $car['interior_color'])->value('id');
                        }else{
                            $interior_colorData=null;
                        }


                        

                        // if(in_array(['build'], $car))
                        // {
                            // Year
                            // if(in_array(['year'], $car)){
                                $year = Year::where('name', $car['build']['year'] ?? null)->first();
                                // dd($car);
                                if(!$year)
                                {
                                    Year::Create([
                                        'name'=>$car['build']['year'] ?? null,
                                    ]);
                                }

                                $yearData = Year::where('name', $car['build']['year'])->value('id');
                            // }else{
                            //     $yearData=null;
                            // }

                            // Make
                            // if(in_array(['make'], $car)){
                                $car_make = Make::where('name', $car['build']['make'] ?? null)->first();
                                // dd($car);
                                if(!$car_make)
                                {
                                    Make::Create([
                                        'name'=>$car['build']['make'] ?? null,
                                    ]);
                                }

                                $CarMakeData = Make::where('name', $car['build']['make'])->value('id');
                            // }else{
                            //     $CarMakeData=null;
                            // }

                            // Model
                            // if(in_array(['model'], $car)){
                                $car_model = Carmodel::where('name', $car['build']['model'] ?? null)->first();
                                // dd($car);
                                if(!$car_model)
                                {
                                    Carmodel::Create([
                                        'name'=>$car['build']['model'] ?? null,
                                    ]);
                                }

                                $CarModelData = Carmodel::where('name', $car['build']['model'])->value('id');
                            // }else{
                            //     $CarModelData=null;
                            // }


                            // Trim
                            // if(in_array(['trim'], $car)){
                                $trim = Trim::where('name', $car['build']['trim'] ?? null)->first();
                                // dd($car);
                                if(!$trim)
                                {
                                    Trim::Create([
                                        'name'=>$car['build']['trim'] ?? null,
                                    ]);
                                }

                                $TrimData = Trim::where('name', $car['build']['trim'])->value('id');
                            // }else{
                            //     $TrimData=null;
                            // }


                            // Version
                            // if(in_array(['version'],$car['build'])){
                                $version = Version::where('name', $car['build']['version'] ?? null)->first();
                                // dd($car);
                                if(!$version)
                                {
                                    Version::Create([
                                        'name'=>$car['build']['version'] ?? null,
                                    ]);
                                }

                                $VersionData = Version::where('name', $car['build']['version'])->value('id');
                            // }else{
                            //     $VersionData=null;
                            // }


                            // Body Type
                            // if(in_array(['body_type'],$car)){
                                $body_type = Body_Type::where('name', $car['build']['body_type'] ?? null)->first();
                                // dd($car);
                                if(!$body_type)
                                {
                                    Body_Type::Create([
                                        'name'=>$car['build']['body_type'] ?? null,
                                    ]);
                                }

                                $BodyTypeData = Body_Type::where('name', $car['build']['body_type'])->value('id');
                            // }else{
                            //     $BodyTypeData=null;
                            // }


                            // Body Sub Type
                            // if(in_array(['body_subtype'], $car['build'])){
                            //     $body_subtype = BodySubType::where('name', $car['build']['body_subtype'] ?? null)->first();
                            //     // dd($body_subtype);
                            //     if(!$body_subtype)
                            //     {
                            //         BodySubType::Create([
                            //             'name'=>$car['build']['body_subtype'],
                            //         ]);
                            //     }

                            //     $BodySubTypeData = BodySubType::where('name', $car['build']['body_subtype'])->value('id');
                            // }else{
                            //     $BodySubTypeData=null;
                            // }

                            $body_subtypeData = null;
                            if (!empty($car['build']['body_subtype'])) {
                                $body_subtype = BodySubType::firstOrCreate(
                                    ['name' => $car['build']['body_subtype']]
                                );
                                $body_subtypeData = $body_subtype->id;
                            }


                            // Vehicle Type
                            // if(in_array(['vehicle_type'], $car)){
                                $vehicle_type = VehicleType::where('name', $car['build']['vehicle_type'] ?? null)->first();
                                // dd($car);
                                if(!$vehicle_type)
                                {
                                    VehicleType::Create([
                                        'name'=>$car['build']['vehicle_type'] ?? null,
                                    ]);
                                }

                                $VehicleTypeData = VehicleType::where('name', $car['build']['vehicle_type'])->value('id');
                            // }else{
                            //     $VehicleTypeData=null;
                            // }


                            // Transmission
                            // if(in_array(['transmission'], $car)){
                                $transmission = Transmission::where('name', $car['build']['transmission'] ?? null)->first();
                                // dd($car);
                                if(!$transmission)
                                {
                                    Transmission::Create([
                                        'name'=>$car['build']['transmission'] ?? null,
                                    ]);
                                }

                                $TransmissionData = Transmission::where('name', $car['build']['transmission'])->value('id');
                            // }else{
                            //     $TransmissionData=null;
                            // }


                            // Drive Train
                            // if(in_array(['drive_train'], $car['build'])){
                                $drive_train = DriveTrain::where('name', $car['build']['drivetrain'] ?? null)->first();
                                // dd($car);
                                if(!$drive_train)
                                {
                                    DriveTrain::Create([
                                        'name'=>$car['build']['drivetrain'] ?? null,
                                    ]);
                                }

                                $drivetrainData = DriveTrain::where('name', $car['build']['drivetrain'])->value('id');
                            // }else{
                            //     $drivetrainData=null;
                            // }


                            // Fuel Type
                            // if(in_array(['fuel_type'], $car)){
                                $fuel_type = Fuel_type::where('name', $car['build']['fuel_type'] ?? null)->first();
                                // dd($car);
                                if(!$fuel_type)
                                {
                                    Fuel_type::Create([
                                        'name'=>$car['build']['fuel_type'] ?? null,
                                    ]);
                                }

                                $fueltypeData = Fuel_type::where('name', $car['build']['fuel_type'])->value('id');
                            // }else{
                            //     $fueltypeData=null;
                            // }


                            // Engine
                            if(in_array(['engine'], $car)){
                                $engine = Engine::where('name', $car['build']['engine'] ?? null)->first();
                                // dd($car);
                                if(!$engine)
                                {
                                    Engine::Create([
                                        'name'=>$car['build']['engine'] ?? null,
                                    ]);
                                }

                                $engineData = Engine::where('name', $car['build']['engine'])->value('id');
                            }else{
                                $engineData=null;
                            }


                            // Engine Size
                            if(in_array(['engine_size'], $car)){
                                $engine_size = EngineSize::where('name', $car['build']['engine_size'] ?? null)->first();
                                // dd($car);
                                if(!$engine_size)
                                {
                                    EngineSize::Create([
                                        'name'=>$car['build']['engine_size'] ?? null,
                                    ]);
                                }

                                $enginesizeData = EngineSize::where('name', $car['build']['engine_size'])->value('id');
                            }else{
                                $enginesizeData=null;
                            }


                            // Engine Block
                            if(in_array(['engine_block'], $car)){
                                $engine_block = EngineBlock::where('name', $car['build']['engine_block'] ?? null)->first();
                                // dd($car);
                                if(!$engine_block)
                                {
                                    EngineBlock::Create([
                                        'name'=>$car['build']['engine_block'] ?? null,
                                    ]);
                                }

                                $engineblockData = EngineBlock::where('name', $car['build']['engine_block'])->value('id');
                            }else{
                                $engineblockData=null;
                            }


                            // Door
                            // if(in_array(['doors'], $car['build'])){
                                $door = Door::where('name', $car['build']['doors'] ?? null)->first();
                                // dd($car);
                                if(!$door)
                                {
                                    Door::Create([
                                        'name'=>$car['build']['doors'] ?? null,
                                    ]);
                                }

                                $doorData = Door::where('name', $car['build']['doors'])->value('id');
                            // }else{
                            //     $doorData=null;
                            // }
                            

                            // Cylinder
                            if(in_array(['cylinders'], $car)){
                                $cylinder = Cylinder::where('name', $car['build']['cylinders'] ?? null)->first();
                                // dd($car);
                                if(!$cylinder)
                                {
                                    Cylinder::Create([
                                        'name'=>$car['build']['cylinders'] ?? null,
                                    ]);
                                }

                                $cylinderData = Door::where('name', $car['build']['cylinders'])->value('id');
                            }else{
                                $cylinderData=null;
                            }

                            

                            // Made In
                            // if(in_array(['made_in'], $car)){
                                $made_in = MadeIn::where('name', $car['build']['made_in'] ?? null)->first();
                                // dd($car);
                                if(!$made_in)
                                {
                                    MadeIn::Create([
                                        'name'=>$car['build']['made_in'] ?? null,
                                    ]);
                                }

                                $madeinData = MadeIn::where('name', $car['build']['made_in'])->value('id');
                            // }else{
                            //     $madeinData=null;
                            // }


                            // Overall Height
                            // if(in_array(['overall_height'], $car)){
                                $overall_height = OverallHeight::where('name', $car['build']['overall_height'] ?? null)->first();
                                // dd($car);
                                if(!$overall_height)
                                {
                                    OverallHeight::Create([
                                        'name'=>$car['build']['overall_height'] ?? null,
                                    ]);
                                }

                                $overallheightData = OverallHeight::where('name', $car['build']['overall_height'])->value('id');
                            // }else{
                            //     $overallheightData=null;
                            // }


                            // Overall Length
                            // if(in_array(['overall_length'], $car)){
                                $overall_length = Overalllength::where('name', $car['build']['overall_length'] ?? null)->first();
                                // dd($car);
                                if(!$overall_length)
                                {
                                    OverallLength::Create([
                                        'name'=>$car['build']['overall_length'] ?? null,
                                    ]);
                                }

                                $overalllengthData = OverallLength::where('name', $car['build']['overall_length'])->value('id');
                            // }else{
                            //     $overalllengthData=null;
                            // }


                            // Overall Width
                            // if(in_array(['overall_width'], $car)){
                                $overall_width = OverallWidth::where('name', $car['build']['overall_width'] ?? null)->first();
                                // dd($car);
                                if(!$overall_width)
                                {
                                    OverallWidth::Create([
                                        'name'=>$car['build']['overall_width'] ?? null,
                                    ]);
                                }

                                $overallwidthData = OverallWidth::where('name', $car['build']['overall_width'])->value('id');
                            // }else{
                            //     $overallwidthData=null;
                            // }


                            // Std Seating
                            // if(in_array(['std_seating'], $car)){
                                $std_seating = StdSeating::where('name', $car['build']['std_seating'] ?? null)->first();
                                // dd($car);
                                if(!$std_seating)
                                {
                                    StdSeating::Create([
                                        'name'=>$car['build']['std_seating'] ?? null,
                                    ]);
                                }

                                $std_seatingData = StdSeating::where('name', $car['build']['std_seating'])->value('id');
                            // }else{
                            //     $std_seatingData=null;
                            // }



                            // PowerTrain Type
                            if(in_array(['powertrain_type'], $car)){
                                $powertrain_type = PowertrainType::where('name', $car['build']['powertrain_type'] ?? null)->first();
                                // dd($car);
                                if(!$powertrain_type)
                                {
                                    PowertrainType::Create([
                                        'name'=>$car['build']['powertrain_type'],
                                    ]);
                                }

                                $powertrain_typeData = PowertrainType::where('name', $car['build']['powertrain_type'])->value('id');
                            }else{
                                $powertrain_typeData=null;
                            }

                            // Highway Mpg
                            if(in_array(['highway_mpg'], $car)){
                                $highway_mpg = HighwayMpg::where('name', $car['build']['highway_mpg'] ?? null)->first();
                                // dd($car);
                                if(!$highway_mpg)
                                {
                                    HighwayMpg::Create([
                                        'name'=>$car['build']['highway_mpg'],
                                    ]);
                                }

                                $highway_mpgData = HighwayMpg::where('name', $car['build']['highway_mpg'])->value('id');
                            }else{
                                $highway_mpgData=null;
                            }

                            // City Mpg
                            if(in_array(['city_mpg'], $car)){
                                $city_mpg = CityMpg::where('name', $car['build']['city_mpg'] ?? null)->first();
                                // dd($car);
                                if(!$city_mpg)
                                {
                                    CityMpg::Create([
                                        'name'=>$car['build']['city_mpg'],
                                    ]);
                                }

                                $city_mpgData = CityMpg::where('name', $car['build']['city_mpg'])->value('id');
                            }else{
                                $city_mpgData=null;
                            }
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
                                            'trim'=>$TrimData??null,
                                            'version'=>$VersionData??null,
                                            'body_type'=>$BodyTypeData??null,
                                            'body_subtype'=>$body_subtypeData??null,
                                            'vehicle_type'=>$VehicleTypeData??null,
                                            'transmission'=>$TransmissionData??null,
                                            'drivetrain'=>$drivetrainData??null,
                                            'fuel_type'=>$fueltypeData??null,
                                            'engine'=>$engineData??null,
                                            'engine_size'=>$enginesizeData??null,
                                            'engine_block'=>$engineblockData??null,
                                            'doors'=>$doorData??null,
                                            'cylinders'=>$cylinderData??null,
                                            'made_in'=>$madeinData??null,
                                            'overall_height'=>$overallheightData??null,
                                            'overall_length'=>$overalllengthData??null,
                                            'overall_width'=>$overallwidthData??null,
                                            'std_seating'=>$std_seatingData??null,
                                            'highway_mpg'=>$highway_mpgData??null,
                                            'city_mpg'=>$city_mpgData??null,
                                            'powertrain_type'=>$powertrain_typeData??null,
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



