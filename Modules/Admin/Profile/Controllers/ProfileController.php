<?php

namespace Modules\Admin\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;

class ProfileController extends Controller
{
    public function orderList(Request $request)
    {
        $user_id = Auth::id();
        $perPage = $request->input('per_page', 10);

        $data = Checkout::with('carlist')->where('user_id', $user_id)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate($perPage);

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

    public function orderItem($order_id)
    {
        // Find the order item by ID
        $orderItem = OrderItems::where('order_id', $order_id)->get();

        if (!$orderItem) {
            // Return a 404 response if the order item is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Order item not found',
            ], 404);
        }

        // Return the order item data if found
        return response()->json([
            'status' => 'success',
            'message' => 'Order item retrieved successfully',
            'data' => $orderItem,
        ], 200);
    }


   


}
