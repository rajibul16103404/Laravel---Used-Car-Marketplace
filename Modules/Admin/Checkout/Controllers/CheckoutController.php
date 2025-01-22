<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Checkout\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Subscriptions\Models\Subscription;
use Modules\Auth\Models\Auth as ModelsAuth;

class CheckoutController extends Controller
{
    public function checkout(Request $request, $order_id)
    {
        $checkout = Checkout::where('order_id', $order_id)->first();

        if (!$checkout) {
            return response()->json(['message' => 'Checkout record not found'], 404);
        }

        // Update the record
        $checkout->update([
            'full_name' => $request->fullName,
            'phone' => $request->phone,
            'street' => $request->street,
            'city' => $request->state,
            'state' => $request->state,
            'zip' => $request->zip,
            'order_status' => 'confirmed',
        ]);

        return redirect()->route('payment.url',['order_id'=>$order_id],);

    }


    public function ProceedToCheckOut(Request $request){
        $validator = Validator::make($request->all(),[
            'country_code' => 'required|string|max:255',
            'port_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userData = Auth::id();

        $orderId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        $subtotal = 0;
        $total=0;

        $shipping = shipping::where('country_code', $request->country_code)->where('port_code', $request->port_code)->first();

        $cartData = Cart::with('carlist')
            ->where('user_id', Auth::id())
            ->first();

        // dd($cartData->carlist_id);

        $subtotal = $subtotal + $cartData->carlist->price;

        $orderItems = OrderItems::create([
            'order_id' => $orderId,
            'items' => $cartData->carlist_id
        ]);

        $platform = Subscription::where('name', 'Platform Fee')->first();
        // dd($shipping->amount);
        $platformFee = ($subtotal/100)*floatval($platform->amount);
        // dd($shipping->amount+$platformFee+$subtotal);
        

        $checkout = Checkout::create([
            'order_id' => $orderId,
            'amount' => $shipping->amount+$platformFee+$subtotal,
            'user_id' => $userData,
            'country_code'=> $request->country_code,
            'port_code'=> $request->port_code,
            'shipping_fee'=>$shipping->amount,
            'platform_fee'=>$platformFee,
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'delivery_status' => 'pending'
        ]);

        return response([
            'status'=>'Success',
            'orderId'=>$orderId
        ]);
    }


    public function checkoutDetails($order_id){
        $item = OrderItems::where('order_id', $order_id)->first();
        
        $car = Carlist::find($item->items);

        $codes = Checkout::where('order_id', $order_id)->first();

        $shipping = shipping::where('country_code', $codes->country_code)->where('port_code', $codes->port_code)->first();

        $platform = Subscription::where('name', 'Platform Fee')->first();

        $platformFee = ($car->price / 100)* $platform->amount;

        $total = $car->price + $shipping->amount + $platformFee;


        return response([
            'status'=>'Success',
            'heading'=>$car->heading,
            'price'=>$car->price,
            'shippingFee'=> $shipping->amount,
            'platformFee'=>$platformFee,
            'total'=>$total,
            'paymentStatus'=>$codes->payment_status
        ]);
    }


}
