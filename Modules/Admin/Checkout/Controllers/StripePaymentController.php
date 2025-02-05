<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;
use Modules\Admin\Checkout\Models\Transaction;
use Modules\Admin\FeaturedPackage\Models\Featured;
use Modules\Admin\Profile\Models\UserVerified;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\SpotlightPackage\Models\Spotlight;
use Modules\Admin\Subscriptions\Models\Subscription;
use Modules\Auth\Models\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\WebhookEndpoint;

class StripePaymentController extends Controller
{
    // Create a Checkout Session
    public function createCheckoutSession($order_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkoutData = Checkout::where('order_id', $order_id)->first();

        if (!$checkoutData) {
            return response()->json(['error' => 'No pending checkout found for this user.'], 404);
        }
        
        $item = OrderItems::where('order_id', $order_id)->first();
        $car = Carlist::find($item->items);
        $codes = Checkout::where('order_id', $order_id)->first();
        $shipping = shipping::where('country_code', $codes->country_code)->where('port_code', $codes->port_code)->first();
        $platform = Subscription::where('name', 'Platform Fee')->first();
        $platformFee = ($car->price / 100) * $platform->amount;


        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $car->heading,
                    ],
                    'unit_amount' => $car->price * 100,
                ],
                'quantity' => 1,
            ],
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Shipping Fee',
                    ],
                    'unit_amount' => $shipping->amount * 100,
                ],
                'quantity' => 1,
            ],
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Platform Fee',
                    ],
                    'unit_amount' => $platformFee * 100,
                ],
                'quantity' => 1,
            ]
        ];


        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'payment_intent_data'=>[
                    'metadata' => [
                        'module_name'=> 'checkout',
                        'order_id' => $checkoutData->order_id,
                        'order_from'=>$checkoutData->order_from,
                        'car_id'=>$checkoutData->car_id,
                    ],
                ],
                'success_url' => "https://carmarketplace.dkingsolution.org/success/{$checkoutData->order_id}",
                'cancel_url' => "https://carmarketplace.dkingsolution.org/failed/{$checkoutData->order_id}",
            ]);

            return response()->json(['url' => $session->url ?? '' ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Success Page
    public function success()
    {
        return response()->json(['message' => 'Payment successful!']);
    }

    // Cancel Page
    public function cancel()
    {
        return response()->json(['message' => 'Payment canceled!']);
    }

    // Handle Webhooks
    public function webhook(Request $request)
    {
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            $this->webhookResponse($event);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Log the event data for debugging
        Log::info('Stripe Event Received:', ['event' => $event]);

        return response('Webhook handled', 200);
    }

//     public function webhook(Request $request)
// {
//     $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
//     $payload = $request->getContent();
//     $sigHeader = $request->header('Stripe-Signature');

//     try {
//         // Verify and construct the Stripe event
//         $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
//     } catch (SignatureVerificationException $e) {
//         Log::error('Stripe Webhook Signature Verification Failed', ['error' => $e->getMessage()]);
//         return response()->json(['error' => 'Invalid signature'], 400);
//     }

//     // Log the event for debugging
//     Log::info('Stripe Webhook Event Received', ['event' => $event]);

//     // Process the event based on type
//     switch ($event->type) {
//         case 'payment_intent.succeeded':
//             $paymentIntent = $event->data->object;
//             Log::info('Payment succeeded', ['payment_intent' => $paymentIntent->id]);
//             // Handle successful payment (e.g., update order status)
//             break;

//         case 'payment_intent.payment_failed':
//             $paymentIntent = $event->data->object;
//             Log::warning('Payment failed', ['payment_intent' => $paymentIntent->id]);
//             // Handle failed payment (e.g., notify the user)
//             break;

//         case 'checkout.session.completed':
//             $session = $event->data->object;
//             Log::info('Checkout session completed', ['session_id' => $session->id]);
//             // Handle completed checkout (e.g., activate subscription)
//             break;

//         default:
//             Log::warning('Unhandled Stripe Event', ['type' => $event->type]);
//     }

//     return response()->json(['message' => 'Webhook handled'], 200);
// }

    public function webhookResponse($event)
    {
        $charge = $event->data->object;
        $transactionID = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        switch($charge->metadata->module_name){
            case 'checkout':
                switch ($event->type) {
                    case 'payment_intent.succeeded':
                        
                        

                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();
        
                        if ($confirm && $confirm->status === 'succeeded') {

                            $updStatus = Checkout::where('order_id', $confirm->order_id)->first();
        
                            if ($updStatus) {
                                $updStatus->update([
                                    'payment_status' => 'paid',
                                ]);

                                $soldOut = Carlist::find($confirm->car_id);
        
                                // Log::info($soldOut);
                                if ($soldOut) {
                                    $soldOut->update([
                                        'status' => 'sold',
                                    ]);
                                } else {
                                    Log::warning("Car Not Found For : {$confirm->car_id}");
                                }
                            } else {
                                Log::warning("Checkout record not found for order_id: {$confirm->order_id}");
                            }

                            $response = Http::withHeaders([
                                'accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ])->post('https://testpython.versatileitbd.com/whatsapp/message-for-payment-status', [
                                'userId' => $updStatus->user_id,
                                'message' => "Payment Completed For {$soldOut->heading}. Your Order ID is {$confirm->order_id}",
                            ]);

                            return $response->json();
                        } else {
                            Log::warning("Transaction not found or not successful for order_id: {$charge->metadata->order_id}");
                        }
        
        
        
        
                        return response()->json(['message' => 'Charge success data saved successfully'], 200);
        
                    case 'payment_intent.payment_failed':
                        
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);

                        $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();
        
                        if ($confirm && $confirm->status === 'succeeded') {

                            $updStatus = Checkout::where('order_id', $confirm->order_id)->first();

                            $response = Http::withHeaders([
                                'accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ])->post('https://testpython.versatileitbd.com/whatsapp/message-for-payment-status', [
                                'userId' => $updStatus->user_id,
                                'message' => "Payment Failed. Please try Again",
                            ]);

                            return $response->json();
                        } else {
                            Log::warning("Transaction not found or not successful for order_id: {$charge->metadata->order_id}");
                        }
        
                        return response()->json(['message' => 'Charge failure data saved successfully'], 200);
        
                    case 'checkout.session.expired':
        
                        return response()->json(['message' => 'Payment Cancelled'], 200);
        
                    default:
                        return response()->json(['message' => 'Event type not handled'], 200);
                }
            
            case 'featured':
                switch ($event->type) {
                    case 'payment_intent.succeeded':

                        $timestamp = $charge->created;
                        
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();
        
                        if ($confirm && $confirm->status === 'succeeded') {
                            $updStatus = Purchase::where('purchase_id', $confirm->order_id)->first();
        
                            if ($updStatus) {
                                $updStatus->update([
                                    'payment_status' => 'paid',
                                ]);
                            } else {
                                Log::warning("Purchase record not found for order_id: {$confirm->order_id}");
                            }


                            Log::info('Timestamp: ', ['timestamp' => $timestamp]);


                            $package_duration = Spotlight::find($updStatus->package_id);

                            // Check if the package is found and extract the duration attribute, then cast it to an integer
                            $duration = $package_duration ? (int) $package_duration->duration : 0;

                            Log::info('Package Duration: ', ['duration' => $duration]);

                            // Now you can use $duration as an integer, e.g., for adding days


                            // Add package days
                            $future_timestamp = strtotime("+{$duration} days", $timestamp);

                            // Log the future timestamp
                            Log::info('Package Expires Date: ', ['expires' => $future_timestamp ? date('Y-m-d H:i:s', $future_timestamp) : 'Not Found']);


                            // Convert the new date and time to seconds (Unix timestamp)
                            $new_timestamp_seconds = $future_timestamp;

                            Log::info('Package Expires Seconds: ', ['expires' => $new_timestamp_seconds ? $new_timestamp_seconds : 'Not Found']);
        
                            $soldOut = Carlist::find($confirm->car_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'featured' => 1,
                                    'featured_expire' => $new_timestamp_seconds
                                ]);    
        
                            } else {
                                Log::warning("Car Not Found For : {$confirm->car_id}");
                            }
                        } else {
                            Log::warning("Transaction not found or not successful for order_id: {$charge->metadata->order_id}");
                        }

                        return response()->json(['message' => 'Charge success data saved successfully'], 200);
        
                    case 'payment_intent.payment_failed':
                        $charge = $event->data->object;
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        return response()->json(['message' => 'Charge failure data saved successfully'], 200);
        
                    case 'checkout.session.expired':

                        return response()->json(['message' => 'Payment Cancelled'], 200);
        
                        
        
                    default:
                        return response()->json(['message' => 'Event type not handled'], 200);
                }

            case 'spotlight':
                switch ($event->type) {
                    case 'payment_intent.succeeded':

                        $timestamp = $charge->created;

                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();
        
                        if ($confirm && $confirm->status === 'succeeded') {
                            Log::info('Going for update the fields');
                            $updStatus = Purchase::where('purchase_id', $confirm->order_id)->first();
        
                            if ($updStatus) {
                                $updStatus->update([
                                    'payment_status' => 'paid',
                                ]);
                            } else {
                                Log::warning("Purchase record not found for order_id: {$confirm->order_id}");
                            }

                            Log::info('Timestamp: ', ['timestamp' => $timestamp]);


                            $package_duration = Spotlight::find($updStatus->package_id);

                            // Check if the package is found and extract the duration attribute, then cast it to an integer
                            $duration = $package_duration ? (int) $package_duration->duration : 0;

                            Log::info('Package Duration: ', ['duration' => $duration]);

                            // Now you can use $duration as an integer, e.g., for adding days


                            // Add package days
                            $future_timestamp = strtotime("+{$duration} days", $timestamp);

                            // Log the future timestamp
                            Log::info('Package Expires Date: ', ['expires' => $future_timestamp ? date('Y-m-d H:i:s', $future_timestamp) : 'Not Found']);


                            // Convert the new date and time to seconds (Unix timestamp)
                            $new_timestamp_seconds = $future_timestamp;

                            Log::info('Package Expires Seconds: ', ['expires' => $new_timestamp_seconds ? $new_timestamp_seconds : 'Not Found']);

        
                            $soldOut = Carlist::find($confirm->car_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'spotlight' => 1,
                                    'spotlight_expire' => $new_timestamp_seconds
                                ]);
                            } else {
                                Log::warning("Car Not Found For : {$confirm->car_id}");
                            }
                        } else {
                            Log::warning("Transaction not found or not successful for order_id: {$charge->metadata->order_id}");
                        }
        
        
        
        
                        return response()->json(['message' => 'Charge success data saved successfully'], 200);
        
                    case 'payment_intent.payment_failed':
                        $charge = $event->data->object;
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        return response()->json(['message' => 'Charge failure data saved successfully'], 200);
        
                    case 'checkout.session.expired':

                        return response()->json(['message' => 'Payment Cancelled'], 200);
        
                    default:
                        return response()->json(['message' => 'Event type not handled'], 200);
                }

            case 'verified':
                switch ($event->type) {
                    case 'payment_intent.succeeded':
                        
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();
        
                        if ($confirm && $confirm->status === 'succeeded') {
                            $updStatus = UserVerified::where('verification_id', $confirm->order_id)->first();
        
                            if ($updStatus) {
                                $updStatus->update([
                                    'payment_status' => 'paid',
                                    'status' => 'processing'
                                ]);
                            } else {
                                Log::warning("Verification record not found for verification_id: {$confirm->order_id}");
                            }
        
                            $soldOut = Auth::find($confirm->user_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'verified' => 'processing',
                                ]);
        
        
                                
        
                            } else {
                                Log::warning("User Not Found For : {$confirm->car_id}");
                            }
                        } else {
                            Log::warning("Transaction not found or not successful for order_id: {$charge->metadata->order_id}");
                        }
        
        
        
        
                        return response()->json(['message' => 'Charge success data saved successfully'], 200);
        
                    case 'payment_intent.payment_failed':
                        $charge = $event->data->object;
        
                        Transaction::create([
                            'transaction_id' => $transactionID,
                            'payment_id' => $charge->id,
                            'amount' => $charge->amount / 100,
                            'currency' => $charge->currency,
                            'status' => $charge->status,
                            'order_from'=>$charge->metadata->order_from,
                            'order_id' => $charge->metadata->order_id,
                            'car_id' => $charge->metadata->car_id,
                        ]);
        
                        return response()->json(['message' => 'Charge failure data saved successfully'], 200);
        
                    case 'checkout.session.expired':

                        return response()->json(['message' => 'Payment Cancelled'], 200);
        
                    default:
                        return response()->json(['message' => 'Event type not handled'], 200);
                }
        }
    }

    // Create a Webhook Endpoint
    public function createWebhookSecret()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $webhookEndpoint = WebhookEndpoint::create([
                    'url' => env('APP_URL') . '/api/stripe-webhook',
                    // 'enabled_events' => ['payment_intent.succeeded', 'payment_intent.payment_failed','checkout.session.expired']
                    'enabled_events' => ['*'],
                ]);
    
                $webhookSecret = $webhookEndpoint->secret;


            return response()->json(['message' => 'Webhook secret created and stored successfully.', 'secret' => $webhookSecret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function viewWebhookSecret()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $webhookEndpoints = WebhookEndpoint::all();
                    return response()->json([
                        'message' => 'Webhook endpoints retrieved successfully.',
                        'data' => $webhookEndpoints,
                    ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteWebhookSecret($id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        if (!$id) {
            return response()->json(['error' => 'Webhook ID is required.'], 400);
        }

        $deletedEndpoint = WebhookEndpoint::retrieve($id)->delete();
        return response()->json([
            'message' => 'Webhook endpoint deleted successfully.',
            'data' => $deletedEndpoint,
        ]);
    }
}
