<?php

namespace Modules\Admin\CartItem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\Cart;



class CartController extends Controller
{
    
    public function index()
    {
        // Retrieve all cart items for the logged-in user, including related carlist data
        $cartItems = Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->get();

            $subTotal = $cartItems->sum(function ($cartItem) {
                return $cartItem->carlist->price;
            });

        return response()->json([
            'status' => 'success',
            'data' => $cartItems,
            'subTtotal' => $subTotal
        ]);
    }


    // Add item to the cart
    public function add(Request $request)
    {
        $request->validate([
            'carlist_id' => 'required|exists:carlists,id',
        ]);

        $carlist = Carlist::findOrFail($request->carlist_id);

        // Check if the carlist is already in the cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('carlist_id', $carlist->id)
            ->first();

        if(!$cartItem)
        {
            // Add new item
            Cart::create([
                'user_id' => Auth::id(),
                'carlist_id' => $carlist->id,
            ]);
        
            $count= Cart::with('carlist')
                ->where('user_id', Auth::id())
                ->count(); 

            return response()->json([
                'status' => 'success',
                'message' => 'Car added to cart',
                'count'=>$count
            ]);
        }
        else{
            return response()->json([
                'status' => 'success',
                'message' => 'Already added in cart',
            ]);
        }
    }

    // Remove item from the cart
    public function remove($id)
    {

        Cart::where('id',  $id)
            ->where('user_id', Auth::id())
            ->delete();

        $count= Cart::with('carlist')
                ->where('user_id', Auth::id())
                ->count();

        return response()->json([
            'status' => 'success',
            'message' => 'carlist removed from cart',
            'count' => $count
        ]);
    }

}
