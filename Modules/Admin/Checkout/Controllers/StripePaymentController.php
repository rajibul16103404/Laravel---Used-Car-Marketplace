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
use Modules\Admin\SpotlightPackage\Models\Purchase;
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
        
                            $soldOut = Carlist::find($confirm->car_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'featured' => 1,
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
        
                            $soldOut = Carlist::find($confirm->car_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'spotlight' => 1,
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
                            $updStatus = Purchase::where('order_id', $confirm->order_id)->first();
        
                            if ($updStatus) {
                                $updStatus->update([
                                    'payment_status' => 'paid',
                                ]);
                            } else {
                                Log::warning("Checkout record not found for order_id: {$confirm->order_id}");
                            }
        
                            $soldOut = Carlist::find($confirm->user_id);
        
                            // Log::info($soldOut);
                            if ($soldOut) {
                                $soldOut->update([
                                    'verified' => 1,
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
