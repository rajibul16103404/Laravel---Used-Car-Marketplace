<?php

namespace Modules\Admin\Seller_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Seller_Type\Models\SellerType;

class SellerTypeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $seller_type = SellerType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Seller Type Added Successfully',
            'data' => $seller_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $seller_type = seller_type::all();

        // return response()->json([
        //     'message' => 'seller_type data retrieved',
        //     'data' => $seller_type,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = SellerType::paginate($perPage);

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
        $seller_type = SellerType::find($id);

        // Check if product exists
        if (!$seller_type) {
            return response()->json([
                'message' => 'seller_type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Seller Type data retrieved successfully',
            'data' => $seller_type,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the seller_type record
        $seller_type = SellerType::find($id);

        if (!$seller_type) {
            return response()->json(['message' => 'seller_type Not Found'], 404);
        }

        // Update the record
        $seller_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Seller Type Updated Successfully',
            'data' => $seller_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the seller_type record
        $seller_type = SellerType::find($id);

        if (!$seller_type) {
            return response()->json(['message' => 'Seller Type Not Found'], 404);
        }

        // Delete the record
        $seller_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Seller Type Deleted Successfully',
        ], 200);
    }


}
