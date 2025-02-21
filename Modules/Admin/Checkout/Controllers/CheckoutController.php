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
            'carlist_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $userData = Auth::id();

            if (!$userData) {
                return response()->json(['error' => 'Unauthorized user. Please log in.'], 401);
            }

            $orderId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
            $subtotal = 0;

            // Retrieve Shipping Fee
            $shipping = Shipping::where('country_code', $request->country_code)
                ->where('port_code', $request->port_code)
                ->first();

            if (!$shipping) {
                return response()->json(['error' => 'Shipping details not found for the selected port.'], 404);
            }

            // Check if Car exists
            $availableCar = Carlist::find($request->carlist_id);
            if (!$availableCar) {
                return response()->json(['error' => 'Car not found.'], 404);
            }

            if ($availableCar->status === 'sold' || $availableCar->status === 'queued') {
                return response()->json([
                    'status' => 'Warning',
                    'message' => 'This car has been sold or is already in processing.',
                ], 400);
            }

            $subtotal += $availableCar->price;

            // Fetch Platform Fee
            $platform = Subscription::where('name', 'Platform Fee')->first();
            if (!$platform) {
                return response()->json(['error' => 'Platform fee configuration missing.'], 500);
            }

            $platformFee = round(($subtotal / 100) * floatval($platform->amount), 2);
            $totalAmount = $shipping->amount + $platformFee + $subtotal;

            // Create Order Items
            OrderItems::create([
                'order_id' => $orderId,
                'items' => $request->carlist_id,
            ]);

            // Create Checkout Entry
            $checkout = Checkout::create([
                'order_id' => $orderId,
                'car_id' => $request->carlist_id,
                'amount' => $totalAmount,
                'user_id' => $userData,
                'country_code' => $request->country_code,
                'port_code' => $request->port_code,
                'shipping_fee' => $shipping->amount,
                'platform_fee' => $platformFee,
                'payment_status' => 'pending',
                'delivery_status' => 'pending',
                'full_name' => $request->fullName,
                'phone' => $request->phone,
                'street' => $request->street,
                'city' => $request->city, // Fixed typo (was assigning state)
                'state' => $request->state,
                'zip' => $request->zip,
                'order_status' => 'confirmed',
                'order_from' => $request->order_from
            ]);

            // Update Car Status to 'queued'
            $availableCar->update(['status' => 'queued']);

            return redirect()->route('checkout.payment.url', ['order_id' => $orderId]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred during checkout. Please try again.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }



    // public function checkoutDetails($order_id)
    // {
    //     try {
    //         // Validate order_id
    //         if (!$order_id) {
    //             return response()->json(['error' => 'Order ID is required'], 400);
    //         }

    //         // Fetch order item
    //         $item = OrderItems::where('order_id', $order_id)->first();
    //         if (!$item) {
    //             return response()->json(['error' => 'Order item not found'], 404);
    //         }

    //         // Fetch car details
    //         $car = Carlist::find($item->items);
    //         if (!$car) {
    //             return response()->json(['error' => 'Car not found'], 404);
    //         }

    //         // Fetch checkout details
    //         $codes = Checkout::where('order_id', $order_id)->first();
    //         if (!$codes) {
    //             return response()->json(['error' => 'Checkout details not found'], 404);
    //         }

    //         // Fetch shipping details
    //         $shipping = Shipping::where('country_code', $codes->country_code)
    //             ->where('port_code', $codes->port_code)
    //             ->first();
    //         if (!$shipping) {
    //             return response()->json(['error' => 'Shipping details not found'], 404);
    //         }

    //         // Fetch platform fee details
    //         $platform = Subscription::where('name', 'Platform Fee')->first();
    //         if (!$platform) {
    //             return response()->json(['error' => 'Platform fee details not found'], 404);
    //         }

    //         // Calculate platform fee
    //         $platformFee = ($car->price / 100) * $platform->amount;

    //         // Calculate total cost
    //         $total = $car->price + $shipping->amount + $platformFee;

    //         return response()->json([
    //             'status' => 'Success',
    //             'heading' => $car->heading,
    //             'price' => $car->price,
    //             'shippingFee' => $shipping->amount,
    //             'platformFee' => $platformFee,
    //             'total' => $total,
    //             'paymentStatus' => $codes->payment_status
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An unexpected error occurred. Please try again.', 'message' => $e->getMessage()], 500);
    //     }
    // }

}
