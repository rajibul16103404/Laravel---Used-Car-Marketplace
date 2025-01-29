<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Checkout\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\Cart;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Subscriptions\Models\Subscription;
use Modules\Auth\Models\Auth as ModelsAuth;

class CheckoutController extends Controller
{
    // public function checkout(Request $request, $order_id)
    // {
    //     $checkout = Checkout::where('order_id', $order_id)->first();

    //     if (!$checkout) {
    //         return response()->json(['message' => 'Checkout record not found'], 404);
    //     }

    //     // Update the record
    //     $checkout->update([
    //         'full_name' => $request->fullName,
    //         'phone' => $request->phone,
    //         'street' => $request->street,
    //         'city' => $request->state,
    //         'state' => $request->state,
    //         'zip' => $request->zip,
    //         'order_status' => 'confirmed',
    //         'order_from'=>$request->order_from
    //     ]);

    //     return redirect()->route('payment.url',['order_id'=>$order_id],);

    // }


    public function checkout(Request $request){
        $validator = Validator::make($request->all(),[
            'country_code' => 'required|string|max:255',
            'port_code' => 'required|string|max:255',
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'order_from'=>'required|string|max:255',
            'carlist_id'=>'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userData = Auth::id();

        $orderId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        $subtotal = 0;
        $total=0;

        $shipping = shipping::where('country_code', $request->country_code)->where('port_code', $request->port_code)->first();

        $availableCar = Carlist::find($request->carlist_id);

        $subtotal = $subtotal + $availableCar->price;

        $orderItems = OrderItems::create([
            'order_id' => $orderId,
            'items' => $request->carlist_id,
        ]);

        $platform = Subscription::where('name', 'Platform Fee')->first();
        // dd($shipping->amount);
        $platformFee = ($subtotal/100)*floatval($platform->amount);
        // dd($shipping->amount+$platformFee+$subtotal);
        
        
        if($availableCar->status != 'sold' && $availableCar->status != 'queued')
        {
            $checkout = Checkout::create([
                'order_id' => $orderId,
                'car_id'=>$request->carlist_id,
                'amount' => $shipping->amount+$platformFee+$subtotal,
                'user_id' => $userData,
                'country_code'=> $request->country_code,
                'port_code'=> $request->port_code,
                'shipping_fee'=>$shipping->amount,
                'platform_fee'=>$platformFee,
                'payment_status' => 'pending',
                'delivery_status' => 'pending',
                'full_name' => $request->fullName,
                'phone' => $request->phone,
                'street' => $request->street,
                'city' => $request->state,
                'state' => $request->state,
                'zip' => $request->zip,
                'order_status' => 'confirmed',
                'order_from'=>$request->order_from
            ]);



            $queuedCar = Carlist::find($request->carlist_id);
            if($queuedCar){
                $queuedCar->update([
                    'status'=>'queued'
                ]);
            }
        }
        else{
        return response([
                'status'=>'Warning',
                'message'=>'This car has been sold or Processing for checkout'
            ]);
        }

        return redirect()->route('payment.url',['order_id'=>$orderId],);

        // $emptyCart = Cart::where('user_id', $userData);
        //                 if ($emptyCart->exists()) {
        //                     $emptyCart->delete(); // Delete all matching rows
        //                 } else {
        //                     Log::warning("Items Not Found For User ID: {$userData}");
        //                 }

        

        
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
