<?php

namespace Modules\Admin\FeaturedPackage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\FeaturedPackage\Models\Featured;
use Modules\Admin\SpotlightPackage\Models\Purchase;

class FeaturedPackageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $featured = Featured::create([
            'package_name' => $request->name,
            'duration' => $request->duration,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New feature Added Successfully',
            'data' => $featured,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $featured = featured::all();

        // return response()->json([
        //     'message' => 'featured data retrieved',
        //     'data' => $featured,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Featured::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Featured::paginate($perPage);

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
        $featured = Featured::find($id);

        // Check if product exists
        if (!$featured) {
            return response()->json([
                'message' => 'featured not found',
            ], 404);
        }

        return response()->json([
            'message' => 'featured data retrieved successfully',
            'data' => $featured,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the featured record
        $featured = Featured::find($id);

        if (!$featured) {
            return response()->json(['message' => 'featured Not Found'], 404);
        }

        // Update the record
        $featured->update([
            'package_name' => $request->name,
            'duration' => $request->duration,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'featured Updated Successfully',
            'data' => $featured,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the featured record
        $featured = Featured::find($id);

        if (!$featured) {
            return response()->json(['message' => 'featured Not Found'], 404);
        }

        // Delete the record
        $featured->delete();

        // Return success response
        return response()->json([
            'message' => 'featured Deleted Successfully',
        ], 200);
    }

    public function purchaseFeatured(Request $request){


        $validator = Validator::make($request->all(),[
            'package_id' => 'required|string|max:255',
            'car_id' => 'required|string|max:255',
            'promotion' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $userData = Auth::id();

        $purchaseID = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));

        
        
        $purchase = Purchase::create([
            'purchase_id' => $purchaseID,
            'car_id'=>$request->car_id,
            'promotion_name'=>$request->promotion,
            'package_id'=>$request->package_id,
            'user_id' => $userData,
            'purchase_status'=> 'confirmed',
            'payment_status'=>'pending'
        ]);

        if($purchase){
            return redirect()->route('featured.payment.url',['purchase_id'=>$purchaseID],);
        }
        else{
            return response([
                'status'=> 'failed',
                'message'=>'Failed to purchase'
            ]);
        }

    }


}
