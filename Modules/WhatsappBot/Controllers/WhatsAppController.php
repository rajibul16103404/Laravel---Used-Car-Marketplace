<?php

namespace Modules\WhatsappBot\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    public function sendWhatsappMessage(Request $request)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');

        $twilio = new Client($sid, $token);

        try {
            $message = $twilio->messages->create(
                "whatsapp:+8801709015762", // To
                [
                    'from' => $from,
                    'contentSid' => 'HXfa71d1c4e79fd4eb34365af96d155a15',
                    // 'contentVariables' => json_encode(["1" => "12/1", "2" => "3pm"]),
                    // 'body' => 'Here is thmc,39e first message'
                ]
            );

            return response()->json([
                'message' => 'Message sent successfully!',
                'sid' => $message->sid,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}