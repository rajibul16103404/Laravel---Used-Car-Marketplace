<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Modules\Auth\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:auths,email']);

        // Generate a token
        $otp=rand(111111,999999);

        // Insert into password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => Carbon::now()]
        );

        // Create a reset password URL
        // $url = url("/reset-password?token=$token&email=" . urlencode($request->email));

        // Send email
        Mail::to($request->email)->send(new ResetPasswordMail($otp));

        return response()->json(['message' => 'Password reset email sent.'], 200);
    }
}
