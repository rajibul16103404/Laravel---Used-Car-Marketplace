<?php

namespace Modules\Admin\OrderList\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Checkout\Models\Checkout;

class OrderListController extends Controller
{


    public function index(Request $request)
    {
        // $category = Category::all();

        // return response()->json([
        //     'message' => 'Category data retrieved',
        //     'data' => $category,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Checkout::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $qry = Checkout::query();
        if ($request->filled('order_id')) {
            $qry->where('order_id', $request->order_id);
        }
        if ($request->filled('order_from')) {
            $qry->where('order_from', $request->order_from);
        }
        if ($request->filled('phone')) {
            $qry->where('phone', $request->phone);
        }
        if ($request->filled('email')) {
            $qry->where('email', $request->email);
        }
        if ($request->filled('order_status')) {
            $qry->where('order_status', $request->order_status);
        }
        if ($request->filled('payment_status')) {
            $qry->where('payment_status', $request->payment_status);
        }
        if ($request->filled('created_at')) {
            $qry->whereDate('created_at', $request->created_at);
        }
        

        $data = $qry->orderBy('created_at', 'desc')->paginate($perPage);

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
        $orderList = Checkout::find($id);

        // Check if product exists
        if (!$orderList) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Order List retrieved successfully',
            'data' => $orderList,
        ], 200);
    }


}
