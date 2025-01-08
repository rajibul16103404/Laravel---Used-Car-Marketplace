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
        $cartItems = Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $cartItems,
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


        // Add new item
        Cart::create([
            'user_id' => Auth::id(),
            'carlist_id' => $carlist->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Car added to cart',
        ]);
    }

    // Remove item from the cart
    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'carlist removed from cart',
        ]);
    }

}
