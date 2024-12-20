<?php

namespace Modules\Admin\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Users\Models\Auth;

class UserController extends Controller
{

    public function index(Request $request)
    {
        // $users = Auth::where('role',0)->get();

        // return response()->json([
        //     'message' => 'Users data retrieved',
        //     'data' => $users,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Auth::paginate($perPage);

        return response()->json([
            'pagination' => [
                'total_count'=>$data->total(),
                'total_page'=>$data->lastPage(),
                'current_page'=>$data->currentPage(),
                'current_page_count'=>$data->count(),
                'next_page' => $data->hasMorePages() ? $data->currentPage()+1 : null,
                'previous_page'=>$data->onFirstPage() ? null : $data->currentPage()
            ],
            'message' => 'Data Retrieved Successfully',
            'data' => $data->items(),
        ],200);
    }

    public function show($id)
    {
        // Find product by ID
        $user = Auth::find($id);
    
        // Check if product exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'User data retrieved successfully',
            'data' => $user,
        ], 200);
    }
    
}
