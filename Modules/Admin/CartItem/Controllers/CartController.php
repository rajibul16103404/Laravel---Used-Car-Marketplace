<?php

namespace Modules\Admin\CartItem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Subscriptions\Models\Subscription;

class CartController extends Controller
{

    public function PlatformFee($car_id){

        $subtotal=0;

        $availableCar = Carlist::find($car_id);

        $subtotal = $subtotal + $availableCar->price;

        $platform = Subscription::where('name', 'Platform Fee')->first();

        $platformFee = ($subtotal/100)*floatval($platform->amount);

        return response([
            'status'=>'Success',
            'platform'=>$platformFee,
        ]);
    }
    
    public function index(Request $request)
    {
        $request->validate([
            'country' => 'nullable|string',
            'port' => 'nullable|string',
        ]);

        $country = $request->country;
        $port = $request->port;

        // Attempt to find the shipping rate
        $rate = shipping::where('country_code', $country)->where('port_code', $port)->first();

        // if (!$rate) {
        //     // Return 404 response if no rate is found
        //     return response()->json([
        //         'error' => 'Shipping rate not found for the specified country and port.',
        //     ], 404);
        // }

        // Return the found shipping rate
        if($country!=null && $port!=null){
            $amount = $rate->amount;
        }
        else{
            $amount=0;
        }


        

        return response()->json([
            'status' => 'success',
            'data'=>$amount,
        ]);
    }



    public function showAllShippingRates(){
        $rates = shipping::all();
        return response([
            'status'=>'success',
            'data'=>$rates
        ]);
    }



}
