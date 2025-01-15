<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Checkout\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Auth\Models\Auth as ModelsAuth;

class CheckoutController extends Controller
{
    public function checkout()
    {
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
            'payment_status' => 'pending'
        ]);

        return redirect()->route('payment.url');

    }


}
