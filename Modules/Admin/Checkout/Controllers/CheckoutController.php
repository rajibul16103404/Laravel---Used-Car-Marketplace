<?php

namespace Modules\Admin\Checkout\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $userData = Auth::id();
        
        if(!$userData){
            return response([
                'message' => 'Unauthorized'
            ], );
        }
        else{
            return response([
                'message' => 'Authorized'
            ]);
        }
    }


}
