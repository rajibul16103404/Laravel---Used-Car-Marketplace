<?php

namespace Modules\Admin\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\Checkout\Models\Transaction;
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

class VerifiedStripePaymentController extends Controller
{
    // Create a Checkout Session
    public function createVerifiedCheckoutSession($verification_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $verifiedData = UserVerified::where('verification_id', $verification_id)->first();

        $packageData = Subscription::where('name', 'verified')->first();

        // dd($packageData);


        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Verified with lifetime access',
                    ],
                    'unit_amount' => $packageData->amount * 100,
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
                        'module_name'=>'verified',
                        'order_id' => $verification_id,
                        'order_from'=>'app',
                        'car_id'=>null,
                    ],
                ],
                'success_url' => "https://carmarketplace.dkingsolution.org/success/{$verification_id}",
                'cancel_url' => "https://carmarketplace.dkingsolution.org/failed/{$verification_id}",
            ]);

            return response()->json(['url' => $session->url ?? ''], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   
}
