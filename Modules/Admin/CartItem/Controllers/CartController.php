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

    public function PlatformFee($car_id)
    {
        try {
            $subtotal = 0;

            // Check if the car exists
            $availableCar = Carlist::find($car_id);
            if (!$availableCar) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Car not found',
                ], 404);
            }

            $subtotal += $availableCar->price;

            // Check if the platform fee record exists
            $platform = Subscription::where('name', 'Platform Fee')->first();
            if (!$platform || !isset($platform->amount) || !is_numeric($platform->amount)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Platform fee configuration not found or invalid',
                ], 500);
            }

            // Calculate platform fee
            $platformFee = ($subtotal / 100) * floatval($platform->amount);

            return response()->json([
                'status' => 'Success',
                'platform' => round($platformFee, 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    
    public function index(Request $request)
    {
        $validated = $request->validate([
            'country' => 'nullable|string',
            'port' => 'nullable|string',
        ]);

        try {
            $country = $request->country;
            $port = $request->port;
            $amount = 0;

            if ($country && $port) {
                $rate = Shipping::where('country_code', $country)
                    ->where('port_code', $port)
                    ->first();

                if ($rate) {
                    $amount = $rate->amount;
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Shipping rate not found for the given country and port.',
                    ], 404);
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $amount,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving shipping rates. Please try again.',
            ], 500);
        }
    }




    public function showAllShippingRates()
    {
        try {
            $rates = Shipping::all();

            if ($rates->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No shipping rates found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $rates,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve shipping rates. Please try again.',
                'error' => $e->getMessage(), // Optional: remove in production for security
            ], 500);
        }
    }




}
