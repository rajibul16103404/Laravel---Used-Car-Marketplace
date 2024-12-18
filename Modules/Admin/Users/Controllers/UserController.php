<?php

namespace Modules\Admin\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Users\Models\Auth;

class UserController extends Controller
{

    public function index()
    {
        $users = Auth::where('role',0)->get();

        return response()->json([
            'message' => 'Users data retrieved',
            'users' => $users,
        ], 200);
    }
}
