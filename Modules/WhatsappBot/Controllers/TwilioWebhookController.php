<?php

namespace Modules\WhatsappBot\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Security\RequestValidator;
use Twilio\Rest\Client;

class TwilioWebhookController extends Controller
{
    public function handleReply(Request $request)
    {
        $twilioAuthToken = config('services.twilio.auth_token');
        $validator = new RequestValidator($twilioAuthToken);

        $url = $request->fullUrl();
        $twilioSignature = $request->header('X-Twilio-Signature');
        $postData = $request->all();

        if (!$validator->validate($twilioSignature, $url, $postData)) {
            return response()->json(['error' => 'Invalid Twilio request'], 403);
        }

        $from = $request->input('From');
        $body = strtolower(trim($request->input('Body')));

        // Define a response message based on the reply
        $responseMessage = '';
        if (strpos($body, 'hello') !== false) {
            $responseMessage = 'Hi there! How can I help you?';
        } elseif (strpos($body, 'thanks') !== false) {
            $responseMessage = 'You’re welcome!';
        } else {
            $responseMessage = 'I didn’t quite understand that. Can you elaborate?';
        }

        // Send a response
        $twilio = new Client(config('services.twilio.sid'), $twilioAuthToken);

        $twilio->messages->create($from, [
            'from' => config('services.twilio.whatsapp_from'),
            'body' => $responseMessage,
        ]);

        return response()->json(['message' => 'Reply processed successfully'], 200);
    }
}
