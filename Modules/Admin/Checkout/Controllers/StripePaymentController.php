<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;
use Modules\Admin\Checkout\Models\Transaction;
use Modules\Admin\Subscriptions\Models\Subscription;
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

        $userId = Auth::id();
        $checkoutData = Checkout::where('order_id', $order_id)->first();

        if (!$checkoutData) {
            return response()->json(['error' => 'No pending checkout found for this user.'], 404);
        }

        $orderItems = OrderItems::where('order_id', $checkoutData->order_id)->first();

        $item = OrderItems::where('order_id', $order_id)->first();

        $car = Carlist::find($item->items);

        $codes = Checkout::where('order_id', $order_id)->first();

        $shipping = shipping::where('country_code', $codes->country_code)->where('port_code', $codes->port_code)->first();

        $platform = Subscription::where('name', 'Platform Fee')->first();

        $platformFee = ($car->price / 100)* $platform->amount;

        $lineItems = [];

            $items = Carlist::find($orderItems->items);
            // dd($items);

            $lineItems[] = [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $items->heading,
                    ],
                    'unit_amount' => $items->price * 100,
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

        // dd($lineItems);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'metadata' => [
                    'order_id' => $checkoutData->order_id,
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
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Log the event data for debugging
        Log::info('Stripe Event Received:', ['event' => $event]);


        return response('Webhook handled', 200);
    }

    // Create a Webhook Endpoint
    public function createWebhookEndpoint()
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $webhookEndpoint = WebhookEndpoint::create([
                'url' => env('APP_URL') . '/api/stripe-webhook',
                'enabled_events' => ['*'],
            ]);

            $webhookSecret = $webhookEndpoint->secret;

            $this->setEnvValue('STRIPE_WEBHOOK_SECRET', $webhookSecret);

            return response()->json(['message' => 'Webhook secret created and stored successfully.', 'secret' => $webhookSecret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update the .env file
    private function setEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            $envContent = File::get($path);

            if (preg_match('/^' . preg_quote($key) . '=.*/m', $envContent)) {
                $envContent = preg_replace('/^' . preg_quote($key) . '=.*/m', "$key=$value", $envContent);
            } else {
                $envContent .= PHP_EOL . "$key=$value";
            }

            File::put($path, $envContent);
        }
    }

    // Handle Payment Success
    private function handlePaymentSuccess($paymentIntent)
    {
        Transaction::create([
            'user_id' => Auth::id(),
            'payment_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount_received / 100,
            'status' => 'success',
            'metadata' => json_encode($paymentIntent->metadata),
        ]);
    }

    // Handle Payment Failure
    private function handlePaymentFailure($invoice)
    {
        Transaction::create([
            'user_id' => Auth::id(),
            'payment_id' => $invoice->id,
            'amount' => $invoice->amount_due / 100,
            'status' => 'failed',
            'metadata' => json_encode($invoice->metadata),
        ]);
    }
}
