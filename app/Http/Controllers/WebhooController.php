<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhooController extends Controller
{
    public function verify(Request $request)
    {
        // Your verify token (set this in your .env file)
        $verifyToken = env('WHATSAPP_VERIFY_TOKEN', 'lolipop');

        // Check the mode and verify token
        if ($request->hub_mode === 'subscribe' && $request->hub_verify_token === $verifyToken) {
            // Respond with the challenge token
            return response($request->hub_challenge, 200);
        }

        // Unauthorized response
        return response('Verification failed', 403);
    }
}
