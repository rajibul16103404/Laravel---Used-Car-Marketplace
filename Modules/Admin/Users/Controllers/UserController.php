<?php

namespace Modules\Admin\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Users\Models\Auth;
use Modules\Auth\Mail\welcome_mail;

class UserController extends Controller
{

    public function index(Request $request)
    {
        // $users = Auth::where('role',0)->get();

        // return response()->json([
        //     'message' => 'Users data retrieved',
        //     'data' => $users,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Auth::paginate($perPage);

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
        $user = Auth::find($id);
    
        // Check if product exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'User data retrieved successfully',
            'data' => $user,
        ], 200);
    }

    public function getDataFromAPI(){
        // Fetch From API 1
        $apiKey = 'dbzXnPErs9CTXncoAHDAkWQovwHzgmua'; // Replace with your API key
        // $apiKey = 'KHOUDaRN4thXldtn7PMMhtrsXJASlh1y'; // Replace with your API key
        $url = "https://mc-api.marketcheck.com/v2/dealers/car?api_key={$apiKey}&rows=50&sort_order=asc";
        
        try {
            // $currentPage =1;

            // Fetch data from the API
            // do{
                $response = Http::timeout(300)->get($url);
            // dd($response);
            Log::info($response);

            if ($response->successful()) {
                $data = $response->json();

                // dd($data);

                // Check if data is valid
                if (isset($data['dealers']) && is_array($data['dealers'])) {
                    foreach ($data['dealers'] as $dealer) {
                        $existingDealer = Auth::where('dealer_id', $dealer['id'] ?? null)->first();

                        // Fetch or create `ExteriorColor`
                        // $exterior_colorData = null;
                        // if (!empty($dealer['exterior_color'])) {
                        //     $exterior_color = ExteriorColor::firstOrCreate(
                        //         ['name' => $dealer['exterior_color']]
                        //     );
                        //     $exterior_colorData = $exterior_color->id;
                        // }

                        $password = "password";
                        $email_verified_at = date('Y-m-d H:i:s');

                        
                        if(!$existingDealer)
                        {
                            Auth::Create(
                                ['dealer_id'=>$dealer['id'],
                                            'name'=>$dealer['seller_name'],
                                            'email'=>$dealer['seller_email']??null,
                                            'phone'=>$dealer['seller_phone']??null,
                                            'street'=>$dealer['street']??null,
                                            'state'=>$dealer['state']??null,
                                            'city'=>$dealer['city']??null,
                                            'zip'=>$dealer['zip']??null,
                                            'country'=>$dealer['country']??null,
                                            'inventory_url'=>$dealer['inventory_url']??null,
                                            'data_source'=>$dealer['data_source']??null,
                                            'listing_count'=>$dealer['listing_count']??null,
                                            'latitude'=>$dealer['latitude']??null,
                                            'longitude'=>$dealer['longitude']??null,
                                            'dealer_type'=>$dealer['dealer_type']??null,
                                            'password'=>$password??null,
                                            'email_verified_at'=>$email_verified_at??null,
                                        ]);
                            Mail::to($dealer['seller_email'])->send(new welcome_mail($password));
                        }
                    }
                    // Update pagination variables
                    // $totalFetched += count($data['listings']);
                    // $start += $rows;
                    return response()->json(['message' => " Data Stored Successfully."]);
                } 
                // else {
                //     break;
                // }
            } else {
                return response()->json(['message' => 'Failed to fetch data from API.'], $response->status());
            }
            // }while (isset($data['listings']) && count($data['listings']) > 0);
            // return response()->json(['message' => "Fetched and stored  records successfully."]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
        
    }
    
}
