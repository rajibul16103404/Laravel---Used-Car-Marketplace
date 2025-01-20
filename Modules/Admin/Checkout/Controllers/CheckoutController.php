<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Checkout\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Auth\Models\Auth as ModelsAuth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'fullName' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userData = Auth::id();
        
        if(!$userData){
            return response([
                'message' => 'Unauthorized'
            ]);
        }

        $userInfo = ModelsAuth::find($userData);
        $cartData = Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->get();

        $orderId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        $total = 0;

        foreach($cartData as $item){
            $total = $total + $item->carlist->price;

            $orderItems = OrderItems::create([
                'order_id' => $orderId,
                'items' => $item->carlist
            ]);
        }

        $checkout = Checkout::create([
            'order_id' => $orderId,
            'amount' => $total,
            'user_id' => $userData,
            'full_name' => $request->fullName,
            'phone' => $request->phone,
            'street' => $request->street,
            'city' => $request->state,
            'state' => $request->state,
            'zip'=> $request->zip,
            'country'=> $request->country,
            'order_status' => 'confirmed',
            'payment_status' => 'pending',
            'delivery_status' => 'pending'
        ]);

        return redirect()->route('payment.url');

    }


}
