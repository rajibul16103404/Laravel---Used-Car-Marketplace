<?php

namespace Modules\WhatsappBot\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MissaelAnda\Whatsapp\Facade\Whatsapp;
use MissaelAnda\Whatsapp\Messages\TemplateMessage;
use MissaelAnda\Whatsapp\Messages\Components\Body;
use MissaelAnda\Whatsapp\Messages\Components\Parameters\Text;

class WhatsappBotController extends Controller
{
    public function index()
    {
        // Generate a random 6-digit OTP


        // Add country code +88 and format phone number

        // dd(Body::create(['text' => "{$text1} {$text}"]));


        try {
            Whatsapp::send(
                "8801956908646",
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
