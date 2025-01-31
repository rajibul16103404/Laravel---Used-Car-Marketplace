<?php

namespace Modules\Admin\FeaturedPackage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\Checkout\Models\Transaction;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\FeaturedPackage\Models\Featured;
use Modules\Auth\Models\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\WebhookEndpoint;

class FeaturedStripePaymentController extends Controller
{
    // Create a Checkout Session
    public function createFeaturedCheckoutSession($purchase_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $purchaseData = Purchase::where('purchase_id', $purchase_id)->first();

        $carData = Carlist::find($purchaseData->car_id);

        

        $userData = Auth::find($purchaseData->user_id);

        $packageData = Featured::where('id', $purchaseData->package_id)->first();

        // dd($packageData);


        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $packageData->package_name,
                    ],
                    'unit_amount' => $packageData->price * 100,
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
                        'module_name'=>'featured',
                        'order_id' => $purchase_id,
                        'car_id'=>$carData->id,
                        'order_from'=>'app'
                    ],
                ],
                'success_url' => "https://carmarketplace.dkingsolution.org/success/{$purchase_id}",
                'cancel_url' => "https://carmarketplace.dkingsolution.org/failed/{$purchase_id}",
            ]);

            return response()->json(['url' => $session->url ?? ''], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   
}
