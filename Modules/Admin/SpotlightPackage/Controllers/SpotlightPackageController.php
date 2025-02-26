<?php

namespace Modules\Admin\SpotlightPackage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\SpotlightPackage\Models\Spotlight;

class SpotlightPackageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'duration' => 'required|numeric|max:255',
            'price' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $Spotlight = Spotlight::create([
            'package_name' => $request->name,
            'duration' => $request->duration,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Spotlight Package Added Successfully',
            'data' => $Spotlight,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $Spotlight = Spotlight::all();

        // return response()->json([
        //     'message' => 'Spotlight data retrieved',
        //     'data' => $Spotlight,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Spotlight::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Spotlight::paginate($perPage);

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

    public function curlPhp(){
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://testpython.versatileitbd.com/whatsapp/message-for-payment-status', [
            'userId' => (string) Auth::id(),
            'message' => "Payment Completed.",
        ]);

        return $response->json();
    }

    public function show($id)
    {
        // Find product by ID
        $Spotlight = Spotlight::find($id);

        // Check if product exists
        if (!$Spotlight) {
            return response()->json([
                'message' => 'Spotlight not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Spotlight data retrieved successfully',
            'data' => $Spotlight,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'duration' => 'required|numeric|max:255',
            'price' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Spotlight record
        $Spotlight = Spotlight::find($id);

        if (!$Spotlight) {
            return response()->json(['message' => 'Spotlight Not Found'], 404);
        }

        // Update the record
        $Spotlight->update([
            'package_name' => $request->name,
            'duration' => $request->duration,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Spotlight Updated Successfully',
            'data' => $Spotlight,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Spotlight record
        $Spotlight = Spotlight::find($id);

        if (!$Spotlight) {
            return response()->json(['message' => 'Spotlight Not Found'], 404);
        }

        // Delete the record
        $Spotlight->delete();

        // Return success response
        return response()->json([
            'message' => 'Spotlight Deleted Successfully',
        ], 200);
    }

    public function purchaseSpotlight(Request $request){


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

        $packagePrice = Spotlight::where('id', $request->package_id)->first();

        // dd($request->package_id);

        // dd($packagePrice->price);
        
        
        $purchase = Purchase::create([
            'purchase_id' => $purchaseID,
            'car_id'=>$request->car_id,
            'promotion_name'=>$request->promotion,
            'package_id'=>$request->package_id,
            'amount'=>$packagePrice->price,
            'user_id' => $userData,
            'purchase_status'=> 'confirmed',
            'payment_status'=>'pending'
        ]);

        if($purchase){
            return redirect()->route('spotlight.payment.url',['purchase_id'=>$purchaseID],);
        }
        else{
            return response([
                'status'=> 'failed',
                'message'=>'Failed to purchase'
            ]);
        }

    }


}
