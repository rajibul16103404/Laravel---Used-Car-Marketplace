<?php

namespace Modules\Admin\TransactionList\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\Transaction;

class TransactionListController extends Controller
{

    public function index(Request $request)
    {
        // $category = Category::all();

        // return response()->json([
        //     'message' => 'Category data retrieved',
        //     'data' => $category,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Transaction::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Transaction::paginate($perPage);

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
        $transactionList = Transaction::find($id);

        // Check if product exists
        if (!$transactionList) {
            return response()->json([
                'message' => 'Transaction List not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Transaction List data retrieved successfully',
            'data' => $transactionList,
        ], 200);
    }



}
