<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Mail\welcome_mail;
use Modules\Auth\Models\Auth;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:auths,email',
            'otp' => 'required',
            'password' => 'required|string',
        ]);

        // Check the token
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        // Update the user's password
        $user = Auth::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        Mail::to($user->email)->send(new welcome_mail($request->password));

        return response()->json(['message' => 'Password reset successful.'], 200); 
    }
}
