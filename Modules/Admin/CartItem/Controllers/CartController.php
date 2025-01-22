<?php

namespace Modules\Admin\CartItem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Subscriptions\Models\Subscription;

class CartController extends Controller
{
    
    public function index(Request $request)
    {
        // Retrieve all cart items for the logged-in user, including related carlist data
        $cartItems = Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->get();

        $subTotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->carlist->price;
        });

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

        $platform = Subscription::where('name', 'Platform Fee')->first();
        // foreach($platform as $item){
            $platformFee = ($subTotal/100)*($platform->amount);
        // }
        

        return response()->json([
            'status' => 'success',
            'data' => $cartItems,
            'subTotal' => $subTotal,
            'shippingFee'=>$amount,
            'platformFee'=>$platformFee,
            'Sum'=>$subTotal+$amount+$platformFee
        ]);
    }


    // Add item to the cart
    public function add(Request $request)
    {
        $request->validate([
            'carlist_id' => 'required|exists:carlists,id',
        ]);
        $count= Cart::with('carlist')
                    ->where('user_id', Auth::id())
                    ->count(); 

        $carlist = Carlist::findOrFail($request->carlist_id);

        // Check if the carlist is already in the cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('carlist_id', $carlist->id)
            ->first();

        if($count < 1)
        {
            if(!$cartItem)
            {
                if($carlist->price != null){
                    // Add new item
                    Cart::create([
                        'user_id' => Auth::id(),
                        'carlist_id' => $carlist->id,
                    ]);

                    $countCart= Cart::with('carlist')
                        ->where('user_id', Auth::id())
                        ->count(); 
                
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Car added to cart',
                        'count'=>$countCart
                    ]);
                }
                else{
                    return response()->json([
                        'status' => 'success',
                        'message' => 'This Car Has No Price',
                    ]);
                }
            }
            else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'Already added in cart',
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'success',
                'message' => 'Only One Car Can be Allowed',
            ]);
        }
    }


    public function showAllShippingRates(){
        $rates = shipping::all();
        return response([
            'status'=>'success',
            'data'=>$rates
        ]);
    }


    // Remove item from the cart
    public function remove($id)
    {

        Cart::where('id',  $id)
            ->where('user_id', Auth::id())
            ->delete();

        $countCart= Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->count();

        if($countCart===null){
            $count = 0;
        }
        else{
            $count = $countCart;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'carlist removed from cart',
            'count' => $count
        ]);
    }

}
