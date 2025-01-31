<?php

namespace Modules\Admin\SpotlightPackage\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\FeaturedPackage\Models\Featured;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\SpotlightPackage\Models\Spotlight;
use Modules\Auth\Models\Auth as ModelsAuth;

class PurchaseController extends Controller
{
    public function index(Request $request)
{
    $userID = Auth::id();
    $userDetail = ModelsAuth::find($userID);

    if (!$userDetail) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Check if the request is for the first page
    if($request->page === '0'){
        $perPage =  Purchase::count();
    }
    else{
        $perPage = $request->input('per_page', 10);
    }

    // Get purchase data based on role
    $query = Purchase::query();
    if ($userDetail->role === 0) {
        $query->where('user_id', $userID);
    }
    $data = $query->paginate($perPage);

    // Extract unique car_ids and package_ids
    $carIds = $data->pluck('car_id')->unique()->toArray();
    $packageIds = $data->pluck('package_id')->unique()->toArray();

    // Fetch related car and package data in batches
    $cars = Carlist::whereIn('id', $carIds)->pluck('heading', 'id');
    $featuredPackages = Featured::whereIn('id', $packageIds)->get()->keyBy('id');
    $spotlightPackages = Spotlight::whereIn('id', $packageIds)->get()->keyBy('id');

    // Process each purchase entry
    $finalData = $data->map(function ($item) use ($cars, $featuredPackages, $spotlightPackages) {
        $packageData = null;
        if ($item->promotion_name === 'featured' && isset($featuredPackages[$item->package_id])) {
            $packageData = $featuredPackages[$item->package_id];
        } elseif ($item->promotion_name === 'spotlight' && isset($spotlightPackages[$item->package_id])) {
            $packageData = $spotlightPackages[$item->package_id];
        }

        return [
            'purchase' => $item,
            'car' => $cars[$item->car_id] ?? null,
            'package' => $packageData,
        ];
    });

    return response()->json([
        'pagination' => [
            'total_count' => $data->total(),
            'total_page' => $data->lastPage(),
            'current_page' => $data->currentPage(),
            'current_page_count' => $data->count(),
            'next_page' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
            'previous_page' => $data->onFirstPage() ? null : $data->currentPage() - 1,
        ],
        'message' => 'Data Retrieved Successfully',
        'data' => $finalData,
    ], 200);
}


    

    public function show($id)
    {

        $userID = Auth::id();

        $userDetail = ModelsAuth::find($userID);

        if($userDetail->role === 0){
            $Spotlight = Purchase::where('id', $id)->where('user_id', $userID)->first();
        }
        else{
            $Spotlight = Purchase::where('id', $id)->first();
        }
        // Find product by ID
        
        $carData = Carlist::select('heading')->find($Spotlight->car_id);

        if($Spotlight->promotion_name === 'featured'){
            $packageData= Featured::select('package_name', 'duration','price')->find($Spotlight->package_id);   
        }

        if($Spotlight->promotion_name === 'spotlight'){
            $packageData= Spotlight::select('package_name', 'duration','price')->find($Spotlight->package_id);   
        }
          
        
        // Check if product exists
        if (!$Spotlight) {
            return response()->json([
                'message' => 'Spotlight not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Spotlight data retrieved successfully',
            'data' => $Spotlight,
            'car'=>$carData,
            'package'=>$packageData
        ], 200);
    }


}
