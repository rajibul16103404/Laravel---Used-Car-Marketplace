<?php

namespace Modules\Admin\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Auth\Mail\welcome_mail;
use Modules\Auth\Models\Auth;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if($request->page === '0'){
            $perPage =  Auth::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $qry = Auth::query();
        if ($request->filled('email')) {
            $qry->where('email', $request->email);
        }
        if ($request->filled('phone')) {
            $qry->where('phone', $request->phone);
        }
        if ($request->filled('status')) {
            $qry->where('verified', $request->status);
        }

        $data = $qry->orderBy('created_at', 'desc')->paginate($perPage);

        // $data = $qry->where('role', 0)->paginate($perPage);

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

    public function show($id, Request $request)
    {
        $perPage = $request->input('per_page', 10);
    
        // Find user by ID or dealer ID
        $user = Auth::where('dealer_id', $id)->orWhere('id', $id)->first();
    
        // Check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    
        // Retrieve user's orders with pagination
        $orders = Checkout::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate($perPage);
    
        // Retrieve user's car list with pagination
        $carlist = Carlist::where('dealer_id', $user->id)->orderBy('created_at', 'desc')->paginate($perPage);
    
        return response()->json([
            'message' => 'User data retrieved successfully',
            'data' => $user,
            'orders' => [
                'pagination' => [
                    'total_count' => $orders->total(),
                    'total_pages' => $orders->lastPage(),
                    'current_page' => $orders->currentPage(),
                    'current_page_count' => $orders->count(),
                    'next_page' => $orders->hasMorePages() ? $orders->currentPage() + 1 : null,
                    'previous_page' => $orders->onFirstPage() ? null : $orders->currentPage() - 1,
                ],
                'data' => $orders->items(),
            ],
            'carlist' => [
                'pagination' => [
                    'total_count' => $carlist->total(),
                    'total_pages' => $carlist->lastPage(),
                    'current_page' => $carlist->currentPage(),
                    'current_page_count' => $carlist->count(),
                    'next_page' => $carlist->hasMorePages() ? $carlist->currentPage() + 1 : null,
                    'previous_page' => $carlist->onFirstPage() ? null : $carlist->currentPage() - 1,
                ],
                'data' => $carlist->items(),
            ],
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
                        $existingDealer = Auth::where('dealer_id', $dealer['id'] ?? null)->orWhere('email', $dealer['seller_email'])->first();

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
                        else{

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
