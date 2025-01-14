<?php

namespace App\Http\Controllers;

use App\Models\PrivetCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrivatCarController extends Controller
{
    public function index()
    {
        $apiKey = 'KHOUDaRN4thXldtn7PMMhtrsXJASlh1y';
        $baseUrl = "https://mc-api.marketcheck.com/v2/search/car/active";
        $start = 0;
        $rowsPerRequest = 25;
        $requestCount=0;

        try {
            while (true) {
                // Build the URL with pagination
                $url = "{$baseUrl}?api_key={$apiKey}&start={$start}&rows={$rowsPerRequest}";

                // Make the API request
                $response = Http::get($url);

                // Handle rate limit error
                // if ($response->status() == 429) {
                //     $retryAfter = (int) ($response->header('Retry-After') ?: 60); // Default to 60 seconds
                //     Log::info("Rate limit exceeded. Retrying after {$retryAfter} seconds.");
                //     sleep($retryAfter);
                //     continue; // Retry after sleeping
                // }

                // Check if response is successful
                if ($response->successful()) {
                    $cars = $response->json()['cars'];
                    if (empty($cars)) {
                        Log::info('No more cars to process.');
                        break; // Exit loop when no more data
                    }
            foreach ($cars as $car) {
                // Prepare data for insertion
                $insertData =  [
                        'id' => $car['id'],
                        'vin' => $car['vin'],
                        'heading' => $car['heading'] ?? null,
                        'price' => $car['price'] ?? null,
                        'miles' => $car['miles'] ?? null,
                        'msrp' => $car['msrp'] ?? null,
                        'vdp_url' => $car['vdp_url'] ?? null,
                        'carfax_1_owner' => $car['carfax_1_owner'] ?? null,
                        'carfax_clean_title' => $car['carfax_clean_title'] ?? null,
                        'exterior_color' => $car['exterior_color'] ?? null,
                        'interior_color' => $car['interior_color'] ?? null,
                        'base_int_color' => $car['base_int_color'] ?? null,
                        'base_ext_color' => $car['base_ext_color'] ?? null,
                        'dom' => $car['dom'] ?? null,
                        'dom_180' => $car['dom_180'] ?? null,
                        'dom_active' => $car['dom_active'] ?? null,
                        'dos_active' => $car['dos_active'] ?? null,
                        'seller_type' => $car['seller_type'] ?? null,
                        'inventory_type' => $car['inventory_type'] ?? null,
                        'stock_no' => $car['stock_no'] ?? null,
                        'last_seen_at' => $car['last_seen_at'] ?? null,
                        'last_seen_at_date' => $car['last_seen_at_date'] ?? null,
                        'scraped_at' => $car['scraped_at'] ?? null,
                        'scraped_at_date' => $car['scraped_at_date'] ?? null,
                        'first_seen_at' => $car['first_seen_at'] ?? null,
                        'first_seen_at_date' => $car['first_seen_at_date'] ?? null,
                        'first_seen_at_source' => $car['first_seen_at_source'] ?? null,
                        'first_seen_at_source_date' => $car['first_seen_at_source_date'] ?? null,
                        'first_seen_at_mc' => $car['first_seen_at_mc'] ?? null,
                        'first_seen_at_mc_date' => $car['first_seen_at_mc_date'] ?? null,
                        'ref_price' => $car['ref_price'] ?? null,
                        'price_change_percent' => $car['price_change_percent'] ?? null,
                        'ref_price_dt' => $car['ref_price_dt'] ?? null,
                        'ref_miles' => $car['ref_miles'] ?? null,
                        'ref_miles_dt' => $car['ref_miles_dt'] ?? null,
                        'source' => $car['source'] ?? null,
                        'in_transit' => $car['in_transit'] ?? null,
                        'media' => json_encode($car['media']), // Serialize nested data
                        'dealer' => json_encode($car['dealer']), // Serialize nested data
                        'build' => json_encode($car['build']), // Serialize nested data
                    ];

                    Log::info('Insert data:', $insertData);


                // Insert data into the database
                PrivetCar::insert($insertData);
            }
            Log::info('Processed ' . count($cars) . ' cars.');
        } else {
            Log::error('API call failed with status: ' . $response->status());
            break;
        }

    }

    return response()->json(['message' => 'All cars data processed successfully.'], 200);
} catch (\Exception $e) {
    Log::error('Error occurred during API request', ['exception' => $e->getMessage()]);
    return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
}
}

}
