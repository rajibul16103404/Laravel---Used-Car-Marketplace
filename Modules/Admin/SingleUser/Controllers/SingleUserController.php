<?php

namespace Modules\Admin\SingleUser\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Models\Auth;

class SingleUserController extends Controller
{

    public function index()
    {
        $user_id=FacadesAuth::id();
        $userData = Auth::find($user_id);

        if($user_id){
            return response([
                'status'=> 'success',
                'data'=>$userData
            ]);
        }
    }



    public function update(Request $request)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'street' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'zip' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = FacadesAuth::id();

        // Find the UserData record
        $userData = Auth::find($user_id);

        if (!$userData) {
            return response()->json(['message' => 'User Not Found'], 404);
        }

        // Update the record
        $userData->update([
            'name' => $request->name,
            'street' => $request->street,
            'state' => $request->state,
            'city' => $request->city,
            'zip' => $request->zip,
            'country' => $request->country,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Profile Updated Successfully',
            'data' => $userData,
        ], 200);
    }


}
