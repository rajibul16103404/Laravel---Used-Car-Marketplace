<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentController extends Controller
{
    // Create a Checkout Session
    public function createCheckoutSession()
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $userId = Auth::id();
    $checkoutData = Checkout::where('user_id', $userId)->where('payment_status', 'pending')->latest()->first();
    
    // Get all order items for the given order_id
    $orderItems = OrderItems::where('order_id', $checkoutData->order_id)->get();

    // Initialize an empty array to hold line items
    $lineItems = [];

    // Iterate over each order item
    foreach ($orderItems as $orderItem) {
        $items = json_decode($orderItem->items); // Decode the 'items' field (assumed to be JSON)

        // Create line item for each order item
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $items->heading, // Product name
                ],
                'unit_amount' => $items->price * 100, // Price in cents
            ],
            'quantity' => 1, // Quantity of the product
        ];
    }

    try {
        // Create the Stripe checkout session with multiple line items
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems, // Use the line items array built above
            'mode' => 'payment',
            'metadata' => [
                'order_id' => $checkoutData->order_id,
            ],
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json(['url' => $session->url ?? ''], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    // Success Page
    public function success()
    {
        return view('payment.success'); // Create a success view
    }

    // Cancel Page
    public function cancel()
    {
        return view('payment.cancel'); // Create a cancel view
    }

    // (Optional) Handle Webhooks
    public function webhook(Request $request)
    {
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                // Fulfill the purchase, e.g., update database
                break;
        }

        return response('Webhook handled', 200);
    }
}
