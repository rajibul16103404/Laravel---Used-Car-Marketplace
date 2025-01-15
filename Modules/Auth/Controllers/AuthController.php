<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\Auth;
use Modules\Auth\Mail\VerifyEmail; // Custom email Mailable
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Mail\welcome_mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Auth::where('email', $request->email)->first();

        if($user){

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($user->password === 'password' && $request->password === 'password') {
                return response()->json([
                    'message' => 'Your password must be reset.',
                    'email' => $user->email
                ], 403);
            }
            else{
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
        }
        else{
            return response()->json(['message' => 'User Not Found'], 401);
        }

        
    



    }
    public function register(Request $request)
    {
        $findUser = Auth::where('email', $request->email)->orWhere('phone', $request->phone)->first();

        if(!$findUser){
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'dealer_id'=>'nullable|string',
                'name'=>'required|string',
                'email'=>'required|string',
                'otp'=>'nullable|string',
                'email_verified_at'=>'nullable|string',
                'phone'=>'required|string',
                'street'=>'nullable|string',
                'state'=>'nullable|string',
                'city'=>'nullable|string',
                'zip'=>'nullable|string',
                'country'=>'nullable|string',
                'inventory_url'=>'nullable|string',
                'data_source'=>'nullable|string',
                'listing_count'=>'nullable|string',
                'latitude'=>'nullable|string',
                'longitude'=>'nullable|string',
                'status'=>'nullable|string',
                'dealer_type'=>'nullable|string',
                'imageURL'=>'nullable|string',
                'password'=>'required|string',
                'role'=>'nullable|string',
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
            $email = $request->email;

            // Create a new user
            $user = Auth::create([
                'name' => $request->name,
                'email' => $request->email,
                'otp'=>$otp,
                'phone'=> $request->phone,
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

            Mail::to($user->email)->send(new welcome_mail($request->password));

            return response()->json([
                'message' => 'User registered successfully. Please verify your email',
                'token' => $token,
                'email'=>$email,
            ], 201);
        }
        else{
            return response()->json(['message' => 'User Already Registered. Please Login.'], 401);
        }
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $user = Auth::where([
            ['email', '=', $request->email],
            ['otp', '=', $request->otp],
        ])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email or code'], 401);
        }

        $verify = $user->update([
            'email_verified_at' => now(),
            'otp' => null,
        ]);

        return response()->json([
            'message' => 'Verified Successfully.',
        ]);
    }

    

}
