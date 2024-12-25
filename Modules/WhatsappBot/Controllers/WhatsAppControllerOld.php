<?php

namespace Modules\WhatsappBot\Controllers;

use App\Http\Controllers\Controller;
use App\Models\validate_phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MissaelAnda\Whatsapp\Facade\Whatsapp;
use MissaelAnda\Whatsapp\Messages\TemplateMessage;
use Modules\Admin\Users\Models\Auth;
use Modules\Auth\Mail\VerifyEmail;

class WhatsAppController extends Controller
{
    // Verify the callback URL during setup
    public function verifyWebhook(Request $request)
    {
        // Replace 'your_verify_token_here' with your actual verify token
        $verifyToken = 'lolipop';

        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            // Log verification success
            Log::info("Webhook verified successfully.");
            return response($challenge, 200);
        } else {
            // Log verification failure
            Log::warning("Webhook verification failed.");
            return response('Forbidden', 403);
        }
    }

    // Handle incoming webhook events
    public function handleWebhook(Request $request)
    {
        // Log the incoming request payload
        Log::info('Incoming webhook payload:', $request->all());

        // Handle specific webhook events
        $data = $request->all();

        if (!empty($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                if (!empty($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        // Process the change object as per your requirement
                        // $messageProduct = $change['value']['messaging_product'];
                        $phone = $change['value']['messages'][0]['from'];
                        // $message = $change['value']['messages'][0]['text']['body'];
                        // $response_data=json_decode($change);
                        Log::info($change['value']['messages'][0]['from']);

                        try {
                            $phoneNumber = Auth::select('email', 'phone')->where('phone', $phone)->first();
                            if($phoneNumber)
                            {
                                $otp = rand(000000,999999);
                                validate_phone::create([
                                    ['phone'=>$phoneNumber],
                                    ['otp'=>$otp],
                                ]);
                                if(validate_phone::select('phone', 'otp')->where('phone', $phone)->first())
                                {
                                    Mail::to($phoneNumber->email)->send(new VerifyEmail($otp));
                                }
                            }
                            Whatsapp::send(
                                $phone,
                                TemplateMessage::create()
                                    ->name('hello_world')
                                    ->language('en_US')
                
                            );
                            
                
                            return response()->json([
                                'success' => true,
                                'message' => 'Message sent successfully'
                            ]);
                
                        } catch (\Exception $e) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Failed to send OTP: ' . $e->getMessage()
                            ], 500);
                        }
                    }
                }
            }
        }

        // Return a success response to acknowledge receipt
        return response('Event received', 200);
    }
}