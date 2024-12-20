<?php

namespace Modules\WhatsappBot\Controllers;

use App\Http\Controllers\Controller;
use MissaelAnda\Whatsapp\Facade\Whatsapp;
use MissaelAnda\Whatsapp\Messages\TemplateMessage;
use MissaelAnda\Whatsapp\Messages\Components\Body;
use MissaelAnda\Whatsapp\Messages\Components\Parameters\Text;

class WhatsappBotController extends Controller
{
    public function index()
    {
        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Add country code +88 and format phone number
        $phone = '8801956908646';

        try {
            Whatsapp::send($phone, TemplateMessage::create()
                ->name('one_time_password')
                ->language('en_US')
                ->body(Body::create([
                    Text::create($otp),
                ])));

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }
}
