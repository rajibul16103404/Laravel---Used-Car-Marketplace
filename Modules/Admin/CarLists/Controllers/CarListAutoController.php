<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Admin\CarLists\Models\Carlist;

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
                            'year'=>$car['build']['year']??null,
                            'make'=>$car['build']['make']??null,
                            'model'=>$car['build']['model']??null,
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
