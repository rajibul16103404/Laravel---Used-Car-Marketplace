<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\Auth as ModelAuth;
use Modules\Auth\Mail\VerifyEmail; // Custom email Mailable

use Modules\Admin\CartItem\Models\Cart;
use Modules\Auth\Mail\welcome_mail;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login','register','verify']]);
    // }
    public function login(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('Login attempt', [
                'email' => $request->email
            ]);

            // Validate request with custom messages
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Please enter your password',
            ]);

            // Check for validation failures
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing required fields',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the user
            $user = ModelAuth::where('email', $request->email)->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication failed',
                    'errors' => [
                        'email' => ['Invalid credentials']
                    ]
                ], 401);
            }

            // Check if email is verified
            if (!$user->email_verified_at) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email verification required',
                    'errors' => [
                        'email' => ['Please verify your email address before logging in']
                    ]
                ], 403);
            }

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
                'errors' => [
                    'general' => ['An unexpected error occurred. Please try again later.']
                ]
            ], 500);
        }
    }
    public function register(Request $request)
    {
        $findUser = ModelAuth::where('email', $request->email)->orWhere('phone', $request->phone)->first();

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
            $user = ModelAuth::create([
                'name' => $request->name,
                'email' => $request->email,
                'otp'=>$otp,
                'phone'=> $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user = ModelAuth::latest('id')->first();

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

        $user = ModelAuth::where([
            ['email', '=', $request->email],
            ['otp', '=', $request->otp],
        ])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email or code']);
        }

        $verify = $user->update([
            'email_verified_at' => now(),
            'otp' => null,
        ]);

        return response()->json([
            'message' => 'Verified Successfully.',
        ]);
    }

    protected function respondWithToken($token, $count)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'count' => $count
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    public function guard()
    {
        return Auth::guard();
    }

    

}
