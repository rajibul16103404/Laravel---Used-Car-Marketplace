<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\Auth;
use Modules\Auth\Mail\VerifyEmail; // Custom email Mailable
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Auth::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if(!$user->hasVerifiedEmail()){
            return response()->json(['message' => 'Please verify your email address.'],403);
        }

        // Generate token
        $token = $user->createToken('API Token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    



    }
    public function register(Request $request)
    {
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:auths',
            'phone' => 'required|string|unique:auths',
            'address' => 'nullable|text',
            'city'=> 'nullable|string',
            'zip'=> 'nullable|integer|max:5',
            'country'=> 'nullable|string',
            'company_name'=> 'nullable|string',
            'company_address'=> 'nullable|string',
            'company_email'=> 'nullable|string|email',
            'company_phone'=> 'nullable|integer|max:13',
            'imageURL'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = null;

        if ($request->hasFile('imageURL')) {
            // Store the image in the 'public' disk and get the path
            $path = $request->file('imageURL')->store('images', 'public');
            $path = Storage::url($path);
        }

        $otp = rand(111111,999999);

        // Create a new user
        $user = Auth::create([
            'name' => $request->name,
            'email' => $request->email,
            'otp'=>$otp,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'city'=> $request->city,
            'zip'=> $request->zip,
            'country'=> $request->country,
            'company_name'=> $request->company_name,
            'company_address'=> $request->company_address,
            'company_email'=> $request->company_email,
            'company_phone'=> $request->company_phone,
            'imageURL'=> $path,
            'password' => Hash::make($request->password),
        ]);

        $user = Auth::latest('id')->first();

        // $verificationUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),  // URL validity time
        //     ['id' => $user->id, 'hash' => sha1($user->email)]
        // );

        // $otp = rand(111111,999999);

        Mail::to($user->email)->send(new VerifyEmail($otp));

        // Optionally generate an API token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully. Please verify your email',
            'token' => $token,
            'otp'=>$otp,
        ], 201);
    }

    public function verifyEmail(Request $request){
        $request->validate([
            'otp' => 'required|integer',
        ]);

        $user = Auth::where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['message' => 'Wrong Code'], 401);
        }

        $verify = $user->update([
            'email_verified_at'=> now(),
            'otp'=>null,
        ]);

        return response()->json([
            'message' => 'Verified Successfully.',
        ]);
    }
}
