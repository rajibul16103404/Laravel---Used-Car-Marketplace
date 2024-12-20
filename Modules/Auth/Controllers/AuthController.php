<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\Auth;

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

        // Generate token
        $token = $user->createToken('API Token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'user' => $user, $token,
            'token' => $token,
        ]);
    



    }
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:auths',
            'phone' => 'required|integer|unique:auths',
            'address' => 'nullable|text',
            'city'=> 'nullable|string',
            'zip'=> 'nullable|integer|max:5',
            'country'=> 'nullable|string',
            'company_name'=> 'nullable|string',
            'company_address'=> 'nullable|string',
            'company_email'=> 'nullable|string|email',
            'company_phone'=> 'nullable|integer|max:13',
            'imageURL'=> 'nullable|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user
        $user = Auth::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'city'=> $request->city,
            'zip'=> $request->zip,
            'country'=> $request->country,
            'company_name'=> $request->company_name,
            'company_address'=> $request->company_address,
            'company_email'=> $request->company_email,
            'company_phone'=> $request->company_phone,
            'imageURL'=> $request->imageURL,
            'password' => Hash::make($request->password),
        ]);

        // Optionally generate an API token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
