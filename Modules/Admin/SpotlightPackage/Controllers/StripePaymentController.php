<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\SpotlightPackage\Models\Spotlight;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\WebhookEndpoint;

class StripePaymentController extends Controller
{
    // Create a Checkout Session
    public function createCheckoutSession($spotlight_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $purchaseData = Purchase::where()

        $data = Spotlight::find($spotlight_id);

        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $data->package_name,
                    ],
                    'unit_amount' => $data->price * 100,
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
                        'spotlight_id' => $data->id
                    ],
                ],
                'success_url' => "https://carmarketplace.dkingsolution.org/success/{$checkoutData->order_id}",
                'cancel_url' => "https://carmarketplace.dkingsolution.org/failed/{$checkoutData->order_id}",
            ]);

            return response()->json(['url' => $session->url ?? ''], 200);
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
        $transactionID = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));

        switch ($event->type) {
            case 'payment_intent.succeeded':
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

                $confirm = Transaction::where('order_id', $charge->metadata->order_id)->first();

                if ($confirm && $confirm->status === 'succeeded') {
                    $updStatus = Checkout::where('order_id', $confirm->order_id)->first();

                    if ($updStatus) {
                        $updStatus->update([
                            'payment_status' => 'paid',
                        ]);
                    } else {
                        Log::warning("Checkout record not found for order_id: {$confirm->order_id}");
                    }

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
                $charge = $event->data->object;

                $findCar = Checkout::where('order_id', $charge->metadata->order_id);

                if($findCar)
                {
                    $car = Carlist::find($findCar->car_id);

                    if($car)
                    {
                        $car->update([
                            'status'=>null
                        ]);
                    }
                    else{
                        Log::info('Car status not changed');
                    }
                }
                else{
                    Log::info("order_id not found");
                }

            default:
                return response()->json(['message' => 'Event type not handled'], 200);
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
