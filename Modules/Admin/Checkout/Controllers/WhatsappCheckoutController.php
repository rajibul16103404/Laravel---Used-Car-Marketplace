<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\Checkout\Models\OrderItems;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Subscriptions\Models\Subscription;
use Modules\Auth\Mail\welcome_mail;
use Modules\Auth\Models\Auth as ModelsAuth;
use Modules\Auth\Mail\VerifyOrder;

class WhatsappCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|max:255',
            'port_code' => 'required|string|max:255',
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'order_from' => 'required|string|max:255',
            'car_id' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        

    
        $shipping = shipping::select('country', 'port', 'amount')->where('country_code', $request->country_code)
            ->where('port_code', $request->port_code)
            ->first();
    
        if (!$shipping) {
            return response()->json(['error' => 'Shipping details not found'], 404);
        }
    
        $availableCar = Carlist::find($request->car_id);
    
        if (!$availableCar) {
            return response()->json(['error' => 'Car not found'], 404);
        }
    
        if (in_array($availableCar->status, ['sold', 'queued'])) {
            return response()->json(['message' => 'This car is already sold or queued for checkout'], 400);
        }

        $user = ModelsAuth::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

    
        if (!$user) {
            $password = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
            try {
                $user = ModelsAuth::create([
                    'name' => $request->fullName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => bcrypt($password),
                    'street' => $request->street,
                    'city' => $request->state,
                    'state' => $request->state,
                    'zip' => $request->zip
                ]);
                Mail::to($request->email)->send(new welcome_mail($password));
            } catch (Exception $e) {
                return response()->json(['error' => 'User creation or email failed'], 500);
            }
        }
    
        $subtotal = $availableCar->price;
        $platform = Subscription::where('name', 'Platform Fee')->first();
        $platformFee = ($subtotal / 100) * floatval($platform->amount ?? 0);
    
        $orderId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        $otp = rand(111111, 999999);

        OrderItems::create([
            'order_id' => $orderId,
            'items' => $request->car_id,
        ]);
    
        Checkout::create([
            'order_id' => $orderId,
            'car_id' => $request->car_id,
            'amount' => $shipping->amount + $platformFee + $subtotal,
            'user_id' => $user->id,
            'country_code' => $request->country_code,
            'port_code' => $request->port_code,
            'shipping_fee' => $shipping->amount,
            'platform_fee' => $platformFee,
            'payment_status' => 'pending',
            'delivery_status' => 'pending',
            'full_name' => $request->fullName,
            'phone' => $request->phone,
            'street' => $request->street,
            'city' => $request->state,
            'state' => $request->state,
            'zip' => $request->zip,
            'order_status' => 'pending',
            'order_from' => $request->order_from,
            'otp' => $otp,
            'email' => $request->email
        ]);

        $checkoutData = Checkout::select('order_id', 'amount')->where('email', $request->email)->orWhere('phone', $request->phone)->latest('id')->first();

        $Car = Carlist::select('vin', 'heading', 'price')->where('id', $request->car_id)->first();
    
        $availableCar->update(['status' => 'queued']);
    
        try {
            Mail::to($request->email)->send(new VerifyOrder($otp));
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to send verification email'], 500);
        }

        
    
        return response()->json(['success' => true, 'message' => 'Please Check your email for OTP to verify your order.', 'data' => [
        'order' => $checkoutData,
        'car' => $Car,
        'shipping' => $shipping,
        'platformFee' => $platformFee,
        'total'=> $checkoutData->amount
    ],], 200);
    }
    
    public function verifyOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:255',
            'otp' => 'required|numeric|digits:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $checkOtp = Checkout::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->latest('id')
            ->first();
    
        if (!$checkOtp) {
            return response()->json(['error' => 'Invalid OTP or Unauthorized access'], 400);
        }
    
        $checkOtp->update(['otp' => null, 'order_status' => 'confirmed']);

        $userVerified = ModelsAuth::where('phone', $request->phone)->first();
        if($userVerified){
            $userVerified->update([
                'email_verified_at' => now()
            ]);
        }
        else{
            return response(['message'=>'User not found with this phone number.']);
        }
    
        return redirect()->route('checkout.payment.url',['order_id'=>$checkOtp->order_id]);
    }
    
}
