<?php

namespace Modules\Admin\Subscriptions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Subscriptions\Models\Subscription;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'amount' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subscription = Subscription::create([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return response()->json([
            'message' => 'New Subscription Added Successfully',
            'data' => $subscription,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $subscription = subscription::all();

        // return response()->json([
        //     'message' => 'subscription data retrieved',
        //     'data' => $subscription,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Subscription::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Subscription::paginate($perPage);

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
        $subscription = Subscription::find($id);

        // Check if product exists
        if (!$subscription) {
            return response()->json([
                'message' => 'Fee not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Fee data retrieved successfully',
            'data' => $subscription,
        ], 200);
    }

    public function showAmount()
    {
        // Find product by ID
        $subscription = Subscription::where('name', 'verified')->first();

        // Check if product exists
        if (!$subscription) {
            return response()->json([
                'message' => 'Fee not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Fee data retrieved successfully',
            'data' => $subscription,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the subscription record
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Fee Not Found'], 404);
        }

        // Update the record
        $subscription->update([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Fee Updated Successfully',
            'data' => $subscription,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the subscription record
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Fee Not Found'], 404);
        }

        // Delete the record
        $subscription->delete();

        // Return success response
        return response()->json([
            'message' => 'Fee Deleted Successfully',
        ], 200);
    }


}
