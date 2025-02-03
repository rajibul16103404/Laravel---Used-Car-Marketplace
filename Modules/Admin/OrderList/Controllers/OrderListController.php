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

        $data = Checkout::paginate($perPage);

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
