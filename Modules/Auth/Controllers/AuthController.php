<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Modules\Auth\Models\Auth;

class AuthController extends Controller
{

    // public function login(Request $request){
    //     $request->validate([
    //         "email"=> "required|email",
    //         "password"=> "required"
    //     ]);

    //     $user = Auth::where("email", $request->email)->first();
    //     if(!$user || Hash::check($request->password, $user->password)){
    //         return response()->json([
    //             "status"=> "error",
    //             "message"=> "Creadentials Doesn't Match!",
    //         ]);

    //     }
    //     $token = $user->createToken("$user->role")->plainTextToken;
    //     return response()->json([
    //         "status"=> "success",
    //         "token"=> $token,
    //         "user"=> $user,
    //         ]);


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Auth::where('email', $request->email)->first();

        if (!$user || Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate token
        $token = $user->createToken('API Token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    



    }
    public function index()
    {
        return view('auth::index');
    }
}
