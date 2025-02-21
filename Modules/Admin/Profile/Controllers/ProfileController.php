<?php

namespace Modules\Admin\Profile\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Profile\Models\UserVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\CartItem\Models\shipping;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;
use Modules\Admin\City_Mpg\Models\CityMpg;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\DriveTrain\Models\DriveTrain;
use Modules\Admin\Engine\Models\Engine;
use Modules\Admin\Engine_Block\Models\EngineBlock;
use Modules\Admin\Engine_Size\Models\EngineSize;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\Highway_Mpg\Models\HighwayMpg;
use Modules\Admin\Inventory_Type\Models\InventoryType;
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;
use Modules\Admin\Seller_Type\Models\SellerType;
use Modules\Admin\SpotlightPackage\Models\Purchase;
use Modules\Admin\Std_seating\Models\StdSeating;
use Modules\Admin\Subscriptions\Models\Subscription;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\Trim\Models\Trim;
use Modules\Admin\Vehicle_Type\Models\VehicleType;
use Modules\Admin\Version\Models\Version;
use Modules\Admin\Year\Models\Year;
use Modules\Auth\Models\Auth as ModelsAuth;

class ProfileController extends Controller
{
    public function orderList(Request $request)
    {
        $user_id = Auth::id();
        if($request->page === '0'){
            $perPage =  Checkout::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

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

        $item = OrderItems::where('order_id', $order_id)->first();

        $car = Carlist::find($item->items);

        $codes = Checkout::where('order_id', $order_id)->first();

        $shipping = shipping::where('country_code', $codes->country_code)->where('port_code', $codes->port_code)->first();

        $platform = Subscription::where('name', 'Platform Fee')->first();

        $platformFee = ($car->price / 100)* $platform->amount;

        $car = Carlist::find($item->items);

        $data[]=[
            'heading'=>$car->heading,
            'price'=>$car->price,
            'image'=>$car->photo_links,
            'shippingFee'=>$shipping->amount,
            'platformFee'=>$platformFee,
            'total'=>$car->price+$shipping->amount+$platformFee,
            'orderId'=>$order_id,
            'payment_status'=>$codes->payment_status,
            'order_status'=>$codes->order_status,
            'fullname'=>$codes->full_name,
            'phone'=>$codes->phone,
            'street'=>$codes->street,
            'city'=>$codes->city,
            'state'=>$codes->state,
            'zip'=>$codes->zip,
            'country'=>$shipping->country
        ];
        
        return response([
            'status'=>'Success',
            'data'=>$data
        ]);

    }

    public function uploadVerificationDocs(Request $request){
        $verification_id= strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
        $request->validate([
            'photo_id'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'address_doc'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'business_doc'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'photo_id.required' => 'You must upload your photo ID.',
            'address_doc.required' => 'You must upload your address verification document.',
            'business_doc.required' => 'You must upload your business verification document.',
        ]);

        $uploadedFiles = [];

        $uploadedFiles['verification_id'] = $verification_id;
        

        $uploadedFiles['user_id'] = Auth::id();

        // Handle each file separately and store them
        if ($request->hasFile('photo_id')) {
            $photoId = $request->file('photo_id');
            $photoIdName = time() . '_photo_id.' . $photoId->getClientOriginalExtension();
            $photoIdPath = $photoId->storeAs('uploads', $photoIdName, 'public');
            $uploadedFiles['photo_id'] = asset('storage/' . $photoIdPath);
        }

        if ($request->hasFile('address_doc')) {
            $addressDoc = $request->file('address_doc');
            $addressDocName = time() . '_address_doc.' . $addressDoc->getClientOriginalExtension();
            $addressDocPath = $addressDoc->storeAs('uploads', $addressDocName, 'public');
            $uploadedFiles['address_doc'] = asset('storage/' . $addressDocPath);
        }

        if ($request->hasFile('business_doc')) {
            $businessDoc = $request->file('business_doc');
            $businessDocName = time() . '_business_doc.' . $businessDoc->getClientOriginalExtension();
            $businessDocPath = $businessDoc->storeAs('uploads', $businessDocName, 'public');
            $uploadedFiles['business_doc'] = asset('storage/' . $businessDocPath);
        }



        // Store file URLs in the database
        $uploadedRecord = UserVerified::create($uploadedFiles);
        // dd($uploadedRecord);

        return response()->json([
            'message' => 'Files uploaded and stored successfully',
            'files' => $uploadedFiles,
            'database_entry' => $uploadedRecord
        ]);

        // return redirect()->route('verified.payment.url',['verification_id'=>$verification_id],);
    }


    public function verifyUser($user_id){
        $status = UserVerified::select('status', 'user_id')->where('user_id', $user_id)->where('payment_status', 'paid')->first();

        

        $docs = UserVerified::select('photo_id', 'address_doc', 'business_doc')->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        // dd($docs);

        $user = ModelsAuth::select('name', 'email', 'street','state', 'city','zip','country','verified')->where('id',$user_id)->first();

        return response([
            'status'=>'success',
            'docs'=> $docs,
            'user'=>$user,
            'verifyStatus'=>$status
        ]);
        
    }



    public function verifyUserList(Request $request){

        if($request->page === '0'){
            $perPage =  BodySubType::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $user_id = UserVerified::select('user_id')->distinct()->pluck('user_id'); 

        $data = ModelsAuth::whereIn('id', $user_id)
                        ->select('id', 'name', 'email', 'street', 'state', 'city', 'zip', 'country','verified')
                        ->paginate($perPage);


        // $user = ModelsAuth::select('name', 'email', 'street','state', 'city','zip','country')->where('id',$user_id)->first();

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


    public function acceptDoc($user_id){
        $status = UserVerified::where('user_id', $user_id)->where('payment_status', 'paid')->first();

        $updStatus = $status->update([
            'status'=>'accepted'
        ]);

        $user = ModelsAuth::where('id', $user_id)->first();
        $updUserStatus = $user->update([
            'verified' => 'accepted'
        ]);

        return response(['message'=> 'Status Changed To Accepted Successfully.',  'status'=>$status, 'statusUser'=>$user]);
    }

    public function rejectDoc($user_id){
        $status = UserVerified::where('user_id', $user_id)->where('payment_status', 'paid')->first();

        $updStatus = $status->update([
            'status'=>'rejected'
        ]);

        $user = ModelsAuth::where('id', $user_id)->first();


        // dd($user);
        $updUserStatus = $user->update([
            'verified' => 'rejected'
        ]);

        return response(['message'=> 'Status Changed To Rejected Successfully.', 'status'=>$status, 'statusUser'=>$user]);
    }

    public function countTotal(){
        $user = ModelsAuth::where('role', 0)->count();

        $car = Carlist::count();

        $latestCar = Carlist::orderBy('created_at', 'desc')->limit(5)->get();

        $orders = Checkout::count();

        $verify = UserVerified::count();

        $platformFee = Checkout::sum('platform_fee');

        return response([
            'status'=>'success',
            'data'=>[
                'user'=>$user,
                'car'=>$car,
                'order'=>$orders,
                'verify'=>$verify,
                'earning'=>$platformFee,
                'latestCar'=>$latestCar
            ]
        ]);
    }


    public function userDashboard(){
        $user = ModelsAuth::where('role', 0)->where('id', Auth::id())->first();

        $car = Carlist::where('dealer_id', $user->id)->count();

        $latestCar = Carlist::where('dealer_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get();

        $orders = Checkout::where('user_id', $user->id)->count();

        $purchase = Purchase::where('user_id', $user->id)->count();

        $purchaseSum = Purchase::where('user_id', $user->id)->sum('amount');

        $orderSum = Checkout::where('user_id', $user->id)->sum('amount');

        return response([
            'status'=>'success',
            'data'=>[
                'user'=>$user->verified,
                'car'=>$car,
                'order'=>$orders,
                'purchase'=>$purchase,
                'packagePurchase'=>$purchaseSum,
                'orderCar'=>$orderSum,
                'latestCar'=>$latestCar
            ]
        ]);
    }

    public function changePF(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');

            $imageUrl = Storage::url($imagePath);

            $fullUrl = env('APP_URL').$imageUrl;

            $userID = Auth::id();

            $image = ModelsAuth::find($userID);

            $image->update([
                'imageURL'=>$fullUrl
            ]);

            return response()->json(['message' => 'Image uploaded successfully', 'image_url' => $fullUrl]);
        }

        return response()->json(['message' => 'Image upload failed'], 400);

    }
}
