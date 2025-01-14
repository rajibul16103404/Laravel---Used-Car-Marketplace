<?php

use App\Http\Controllers\PrivatCarController;
use Modules\Admin\CarLists\Controllers\AllDropController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Body_Subtype\Controllers\BodySubTypeController;
use Modules\Admin\Body_Type\Controllers\Body_TypeController;
use Modules\Admin\CartItem\Controllers\CartController;
use Modules\Admin\CarLists\Controllers\CarListAutoController;
use Modules\Admin\CarLists\Controllers\CarListController;
use Modules\Admin\CarModel\Controllers\CarModelController;
use Modules\Admin\Checkout\Controllers\CheckoutController;
use Modules\Admin\City_Mpg\Controllers\CityMpgController;
use Modules\Admin\Color\ExteriorColor\Controllers\ExteriorColorController;
use Modules\Admin\Color\InteriorColor\Controllers\InteriorColorController;
use Modules\Admin\Doors\Controllers\DoorController;
use Modules\Auth\Controllers\AuthController;
use Modules\Auth\Controllers\ForgotPasswordController;
use Modules\Auth\Controllers\ResetPasswordController;
use Modules\Admin\Users\Controllers\UserController;
use Modules\Admin\Cylinders\Controllers\CylinderController;
use Modules\Admin\DriveTrain\Controllers\DriveTrainController;
use Modules\Admin\Engine\Controllers\EngineController;
use Modules\Admin\Engine_Block\Controllers\EngineBlockController;
use Modules\Admin\Engine_Size\Controllers\EngineSizeController;
use Modules\Admin\Fuel_Type\Controllers\Fuel_TypeController;
use Modules\Admin\Highway_Mpg\Controllers\HighwayMpgController;
use Modules\Admin\Inventory_Type\Controllers\InventoryTypeController;
use Modules\Admin\MadeIn\Controllers\MadeInController;
use Modules\Admin\Make\Controllers\MakeController;
use Modules\Admin\Overall_Height\Controllers\OverallHeightController;
use Modules\Admin\Overall_Length\Controllers\OverallLengthController;
use Modules\Admin\Overall_Width\Controllers\OverallWidthController;
use Modules\Admin\Seller_Type\Controllers\SellerTypeController;
use Modules\Admin\SingleUser\Controllers\SingleUserController;
use Modules\Admin\Std_seating\Controllers\StdSeatingController;
use Modules\Admin\Subscriptions\Controllers\SubscriptionController;
use Modules\Admin\Transmission\Controllers\TransmissionController;
use Modules\Admin\Trim\Controllers\TrimController;
use Modules\Admin\Vehicle_Type\Controllers\VehicleTypeController;
use Modules\Admin\Version\Controllers\VersionController;
use Modules\Admin\Year\Controllers\YearController;
use Modules\WhatsappBot\Controllers\TwilioWebhookController;
use Modules\WhatsappBot\Controllers\WhatsappBotController;
use Modules\WhatsappBot\Controllers\WhatsAppController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// demo car list
Route::get('/cars', [PrivatCarController::class, 'index']);

//Car List Routes
Route::prefix('/public/car-list')->group(function(){
    Route::get('/', [CarListController::class, 'index'])->name('index');
    Route::get('/{id}', [CarListController::class, 'show'])->name('single_view');
});


// Webhook
Route::post('/twilio/webhook', [TwilioWebhookController::class, 'handleReply']);
Route::post('/webhook', [WhatsAppController::class, 'sendWhatsappMessage']);
// Route::get('/webhook', [WhatsAppController::class, 'verifyWebhook']);

// Whatsapp

Route::get('/send', [WhatsappBotController::class, 'index'])->name('demo');





Route::get('/dealer', [CarListAutoController::class, 'get_dealer'])->name('dealer_store');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::post('/verify', [AuthController::class, 'verifyEmail'])->name('VerifyEmail');

Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

// Email Verification Route
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email successfully verified.']);
})->name('verification.verify');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return response()->json(['message' => 'Email successfully verified.']);
// })->middleware(['auth:sanctum'])->name('verification.verify');



// Resend verification email
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification email resent.']);
})->middleware('auth:sanctum');


// Route::prefix('/car-list')->group(function(){
//     Route::post('/', [CarListController::class, 'store'])->name('store');
//     Route::get('/', [CarListController::class, 'index'])->name('index');
//     Route::get('/{id}', [CarListController::class, 'show'])->name('single_view');
//     Route::put('/{id}', [CarListController::class, 'update'])->name('update');
//     Route::delete('/{id}', [CarListController::class, 'destroy'])->name('delete');
    
// });



Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('role:admin')->group(function () {


        //Users List Routes
        Route::prefix('/admin/users-list')->group(function(){
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('single_view');
            // Route::get('/get-data', [UserController::class, 'getDataFromAPI'])->name('getData');

        });

        Route::get('admin/get-data', [UserController::class, 'getDataFromAPI'])->name('getData');
       

        //Exterior Color Routes
        Route::prefix('/admin/exterior-color')->group(function(){
            Route::post('/', [ExteriorColorController::class, 'store'])->name('store');
            Route::get('/', [ExteriorColorController::class, 'index'])->name('index');
            Route::get('/{id}', [ExteriorColorController::class, 'show'])->name('single_view');
            Route::put('/{id}', [ExteriorColorController::class, 'update'])->name('update');
            Route::delete('/{id}', [ExteriorColorController::class, 'destroy'])->name('delete');
        });

        //Interior Color Routes
        Route::prefix('/admin/interior-color')->group(function(){
            Route::post('/', [InteriorColorController::class, 'store'])->name('store');
            Route::get('/', [InteriorColorController::class, 'index'])->name('index');
            Route::get('/{id}', [InteriorColorController::class, 'show'])->name('single_view');
            Route::put('/{id}', [InteriorColorController::class, 'update'])->name('update');
            Route::delete('/{id}', [InteriorColorController::class, 'destroy'])->name('delete');
        });

        //Inventory Type Routes
        Route::prefix('/admin/inventory-type')->group(function(){
            Route::post('/', [InventoryTypeController::class, 'store'])->name('store');
            Route::get('/', [InventoryTypeController::class, 'index'])->name('index');
            Route::get('/{id}', [InventoryTypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [InventoryTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [InventoryTypeController::class, 'destroy'])->name('delete');
        });

        //Seller Type Routes
        Route::prefix('/admin/seller-type')->group(function(){
            Route::post('/', [SellerTypeController::class, 'store'])->name('store');
            Route::get('/', [SellerTypeController::class, 'index'])->name('index');
            Route::get('/{id}', [SellerTypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [SellerTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [SellerTypeController::class, 'destroy'])->name('delete');
        });

        //Year Routes
        Route::prefix('/admin/year')->group(function(){
            Route::post('/', [YearController::class, 'store'])->name('store');
            Route::get('/', [YearController::class, 'index'])->name('index');
            Route::get('/{id}', [YearController::class, 'show'])->name('single_view');
            Route::put('/{id}', [YearController::class, 'update'])->name('update');
            Route::delete('/{id}', [YearController::class, 'destroy'])->name('delete');
        });

        //Make Routes
        Route::prefix('/admin/make')->group(function(){
            Route::post('/', [MakeController::class, 'store'])->name('store');
            Route::get('/', [MakeController::class, 'index'])->name('index');
            Route::get('/{id}', [MakeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [MakeController::class, 'update'])->name('update');
            Route::delete('/{id}', [MakeController::class, 'destroy'])->name('delete');
        });

        //Car Model Routes
        Route::prefix('/admin/car-model')->group(function(){
            Route::post('/', [CarModelController::class, 'store'])->name('store');
            Route::get('/', [CarModelController::class, 'index'])->name('index');
            Route::get('/{id}', [CarModelController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CarModelController::class, 'update'])->name('update');
            Route::delete('/{id}', [CarModelController::class, 'destroy'])->name('delete');
        });

        //Trim Routes
        Route::prefix('/admin/trim')->group(function(){
            Route::post('/', [TrimController::class, 'store'])->name('store');
            Route::get('/', [TrimController::class, 'index'])->name('index');
            Route::get('/{id}', [TrimController::class, 'show'])->name('single_view');
            Route::put('/{id}', [TrimController::class, 'update'])->name('update');
            Route::delete('/{id}', [TrimController::class, 'destroy'])->name('delete');
        });

        //Version Routes
        Route::prefix('/admin/version')->group(function(){
            Route::post('/', [VersionController::class, 'store'])->name('store');
            Route::get('/', [VersionController::class, 'index'])->name('index');
            Route::get('/{id}', [VersionController::class, 'show'])->name('single_view');
            Route::put('/{id}', [VersionController::class, 'update'])->name('update');
            Route::delete('/{id}', [VersionController::class, 'destroy'])->name('delete');
        });

        //Body Type Routes
        Route::prefix('/admin/body-type')->group(function(){
            Route::post('/', [Body_TypeController::class, 'store'])->name('store');
            Route::get('/', [Body_TypeController::class, 'index'])->name('index');
            Route::get('/{id}', [Body_TypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [Body_TypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [Body_TypeController::class, 'destroy'])->name('delete');

            Route::get('/paginate', [Body_TypeController::class, 'pagination'])->name('paginate');
        });

        //Body Sub Type Model Routes
        Route::prefix('/admin/body-sub-type')->group(function(){
            Route::post('/', [BodySubTypeController::class, 'store'])->name('store');
            Route::get('/', [BodySubTypeController::class, 'index'])->name('index');
            Route::get('/{id}', [BodySubTypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [BodySubTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [BodySubTypeController::class, 'destroy'])->name('delete');
        });

        //Vehicle Type Model Routes
        Route::prefix('/admin/vehicle-type')->group(function(){
            Route::post('/', [VehicleTypeController::class, 'store'])->name('store');
            Route::get('/', [VehicleTypeController::class, 'index'])->name('index');
            Route::get('/{id}', [VehicleTypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [VehicleTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [VehicleTypeController::class, 'destroy'])->name('delete');
        });

        //Transmisssion Routes
        Route::prefix('/admin/transmission')->group(function(){
            Route::post('/', [TransmissionController::class, 'store'])->name('store');
            Route::get('/', [TransmissionController::class, 'index'])->name('index');
            Route::get('/{id}', [TransmissionController::class, 'show'])->name('single_view');
            Route::put('/{id}', [TransmissionController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransmissionController::class, 'destroy'])->name('delete');
        });

        //Drive Train Routes
        Route::prefix('/admin/drive-train')->group(function(){
            Route::post('/', [DriveTrainController::class, 'store'])->name('store');
            Route::get('/', [DriveTrainController::class, 'index'])->name('index');
            Route::get('/{id}', [DriveTrainController::class, 'show'])->name('single_view');
            Route::put('/{id}', [DriveTrainController::class, 'update'])->name('update');
            Route::delete('/{id}', [DriveTrainController::class, 'destroy'])->name('delete');
        });

        //Fuel_Type Routes
        Route::prefix('/admin/fuel-type')->group(function(){
            Route::post('/', [Fuel_TypeController::class, 'store'])->name('store');
            Route::get('/', [Fuel_TypeController::class, 'index'])->name('index');
            Route::get('/{id}', [Fuel_TypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [Fuel_TypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [Fuel_TypeController::class, 'destroy'])->name('delete');
        });

        //Engine Routes
        Route::prefix('/admin/engine')->group(function(){
            Route::post('/', [EngineController::class, 'store'])->name('store');
            Route::get('/', [EngineController::class, 'index'])->name('index');
            Route::get('/{id}', [EngineController::class, 'show'])->name('single_view');
            Route::put('/{id}', [EngineController::class, 'update'])->name('update');
            Route::delete('/{id}', [EngineController::class, 'destroy'])->name('delete');
        });

        //Engine Size Routes
        Route::prefix('/admin/engine-size')->group(function(){
            Route::post('/', [EngineSizeController::class, 'store'])->name('store');
            Route::get('/', [EngineSizeController::class, 'index'])->name('index');
            Route::get('/{id}', [EngineSizeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [EngineSizeController::class, 'update'])->name('update');
            Route::delete('/{id}', [EngineSizeController::class, 'destroy'])->name('delete');
        });

        //Engine Block Routes
        Route::prefix('/admin/engine-bock')->group(function(){
            Route::post('/', [EngineBlockController::class, 'store'])->name('store');
            Route::get('/', [EngineBlockController::class, 'index'])->name('index');
            Route::get('/{id}', [EngineBlockController::class, 'show'])->name('single_view');
            Route::put('/{id}', [EngineBlockController::class, 'update'])->name('update');
            Route::delete('/{id}', [EngineBlockController::class, 'destroy'])->name('delete');
        });

        //Door Routes
        Route::prefix('/admin/door')->group(function(){
            Route::post('/', [DoorController::class, 'store'])->name('store');
            Route::get('/', [DoorController::class, 'index'])->name('index');
            Route::get('/{id}', [DoorController::class, 'show'])->name('single_view');
            Route::put('/{id}', [DoorController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoorController::class, 'destroy'])->name('delete');
        });

        //Cylinder Routes
        Route::prefix('/admin/cylinder')->group(function(){
            Route::post('/', [CylinderController::class, 'store'])->name('store');
            Route::get('/', [CylinderController::class, 'index'])->name('index');
            Route::get('/{id}', [CylinderController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CylinderController::class, 'update'])->name('update');
            Route::delete('/{id}', [CylinderController::class, 'destroy'])->name('delete');
        });

        //MAde In Routes
        Route::prefix('/admin/made-in')->group(function(){
            Route::post('/', [MadeInController::class, 'store'])->name('store');
            Route::get('/', [MadeInController::class, 'index'])->name('index');
            Route::get('/{id}', [MadeInController::class, 'show'])->name('single_view');
            Route::put('/{id}', [MadeInController::class, 'update'])->name('update');
            Route::delete('/{id}', [MadeInController::class, 'destroy'])->name('delete');
        });

        //Overall Height Routes
        Route::prefix('/admin/overall-height')->group(function(){
            Route::post('/', [OverallHeightController::class, 'store'])->name('store');
            Route::get('/', [OverallHeightController::class, 'index'])->name('index');
            Route::get('/{id}', [OverallHeightController::class, 'show'])->name('single_view');
            Route::put('/{id}', [OverallHeightController::class, 'update'])->name('update');
            Route::delete('/{id}', [OverallHeightController::class, 'destroy'])->name('delete');
        });

        //Overall Length Routes
        Route::prefix('/admin/overall-length')->group(function(){
            Route::post('/', [OverallLengthController::class, 'store'])->name('store');
            Route::get('/', [OverallLengthController::class, 'index'])->name('index');
            Route::get('/{id}', [OverallLengthController::class, 'show'])->name('single_view');
            Route::put('/{id}', [OverallLengthController::class, 'update'])->name('update');
            Route::delete('/{id}', [OverallLengthController::class, 'destroy'])->name('delete');
        });

        //Overall Width Routes
        Route::prefix('/admin/overall-width')->group(function(){
            Route::post('/', [OverallWidthController::class, 'store'])->name('store');
            Route::get('/', [OverallWidthController::class, 'index'])->name('index');
            Route::get('/{id}', [OverallWidthController::class, 'show'])->name('single_view');
            Route::put('/{id}', [OverallWidthController::class, 'update'])->name('update');
            Route::delete('/{id}', [OverallWidthController::class, 'destroy'])->name('delete');
        });

        //STD Seating Routes
        Route::prefix('/admin/std-seating')->group(function(){
            Route::post('/', [StdSeatingController::class, 'store'])->name('store');
            Route::get('/', [StdSeatingController::class, 'index'])->name('index');
            Route::get('/{id}', [StdSeatingController::class, 'show'])->name('single_view');
            Route::put('/{id}', [StdSeatingController::class, 'update'])->name('update');
            Route::delete('/{id}', [StdSeatingController::class, 'destroy'])->name('delete');
        });

        //Highway Mileage Model Routes
        Route::prefix('/admin/highway-mpg')->group(function(){
            Route::post('/', [HighwayMpgController::class, 'store'])->name('store');
            Route::get('/', [HighwayMpgController::class, 'index'])->name('index');
            Route::get('/{id}', [HighwayMpgController::class, 'show'])->name('single_view');
            Route::put('/{id}', [HighwayMpgController::class, 'update'])->name('update');
            Route::delete('/{id}', [HighwayMpgController::class, 'destroy'])->name('delete');
        });

        //City Mileage Model Routes
        Route::prefix('/admin/city-mpg')->group(function(){
            Route::post('/', [CityMpgController::class, 'store'])->name('store');
            Route::get('/', [CityMpgController::class, 'index'])->name('index');
            Route::get('/{id}', [CityMpgController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CityMpgController::class, 'update'])->name('update');
            Route::delete('/{id}', [CityMpgController::class, 'destroy'])->name('delete');
        });


        //Subscription Model Routes
        Route::prefix('/admin/subscription')->group(function(){
            Route::post('/', [SubscriptionController::class, 'store'])->name('store');
            Route::get('/', [SubscriptionController::class, 'index'])->name('index');
            Route::get('/{id}', [SubscriptionController::class, 'show'])->name('single_view');
            Route::put('/{id}', [SubscriptionController::class, 'update'])->name('update');
            Route::delete('/{id}', [SubscriptionController::class, 'destroy'])->name('delete');
        });

        
        //Car List Routes
        Route::prefix('/admin/car-list')->group(function(){
            Route::post('/', [CarListController::class, 'store'])->name('store');
            Route::get('/', [CarListController::class, 'index'])->name('index');
            Route::get('/{id}', [CarListController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CarListController::class, 'update'])->name('update');
            Route::delete('/{id}', [CarListController::class, 'destroy'])->name('delete');
        });


        // SingleUser
        Route::prefix('/admin')->group(function(){
            Route::get('/profile', [SingleUserController::class, 'index'])->name('singleuser');
            Route::put('/profile',[SingleUserController::class, 'update'])->name('updateProfile');
        });


        // Route::get('/admin', function () {
        Route::prefix('/admin')->group(function(){
            Route::get('/all-drop', [AllDropController::class, 'index'])->name('showAllDrop');
            Route::get('/all-cars', [CarListAutoController::class, 'index'])->name('showAllCars');
            Route::get('/vin', [CarListAutoController::class, 'get_vin'])->name('vin_store');
            
            // return response()->json(['message' => 'Welcome, Admin']);
        });
    });

    Route::middleware('role:user')->group(function () {


        //Exterior Color Routes
        Route::prefix('/exterior-color')->group(function(){
            Route::post('/', [ExteriorColorController::class, 'store'])->name('store');
            Route::get('/', [ExteriorColorController::class, 'index'])->name('index');
        });

        //Interior Color Routes
        Route::prefix('/interior-color')->group(function(){
            Route::post('/', [InteriorColorController::class, 'store'])->name('store');
            Route::get('/', [InteriorColorController::class, 'index'])->name('index');
        });

        //Inventory Type Routes
        Route::prefix('/inventory-type')->group(function(){
            Route::post('/', [InventoryTypeController::class, 'store'])->name('store');
            Route::get('/', [InventoryTypeController::class, 'index'])->name('index');
        });

        //Seller Type Routes
        Route::prefix('/seller-type')->group(function(){
            Route::post('/', [SellerTypeController::class, 'store'])->name('store');
            Route::get('/', [SellerTypeController::class, 'index'])->name('index');
        });

        //Year Routes
        Route::prefix('/year')->group(function(){
            Route::post('/', [YearController::class, 'store'])->name('store');
            Route::get('/', [YearController::class, 'index'])->name('index');
        });

        //Make Routes
        Route::prefix('/make')->group(function(){
            Route::post('/', [MakeController::class, 'store'])->name('store');
            Route::get('/', [MakeController::class, 'index'])->name('index');
        });

        //Car Model Routes
        Route::prefix('/car-model')->group(function(){
            Route::post('/', [CarModelController::class, 'store'])->name('store');
            Route::get('/', [CarModelController::class, 'index'])->name('index');
        });

        //Trim Routes
        Route::prefix('/trim')->group(function(){
            Route::post('/', [TrimController::class, 'store'])->name('store');
            Route::get('/', [TrimController::class, 'index'])->name('index');
        });

        //Version Routes
        Route::prefix('/version')->group(function(){
            Route::post('/', [VersionController::class, 'store'])->name('store');
            Route::get('/', [VersionController::class, 'index'])->name('index');
        });

        //Body Type Routes
        Route::prefix('/body-type')->group(function(){
            Route::post('/', [Body_TypeController::class, 'store'])->name('store');
            Route::get('/', [Body_TypeController::class, 'index'])->name('index');
        });

        //Body Sub Type Model Routes
        Route::prefix('/body-sub-type')->group(function(){
            Route::post('/', [BodySubTypeController::class, 'store'])->name('store');
            Route::get('/', [BodySubTypeController::class, 'index'])->name('index');
        });

        //Vehicle Type Model Routes
        Route::prefix('/vehicle-type')->group(function(){
            Route::post('/', [VehicleTypeController::class, 'store'])->name('store');
            Route::get('/', [VehicleTypeController::class, 'index'])->name('index');
        });

        //Transmisssion Routes
        Route::prefix('/transmission')->group(function(){
            Route::post('/', [TransmissionController::class, 'store'])->name('store');
            Route::get('/', [TransmissionController::class, 'index'])->name('index');
        });

        //Drive Train Routes
        Route::prefix('/drive-train')->group(function(){
            Route::post('/', [DriveTrainController::class, 'store'])->name('store');
            Route::get('/', [DriveTrainController::class, 'index'])->name('index');
        });

        //Fuel_Type Routes
        Route::prefix('/fuel-type')->group(function(){
            Route::post('/', [Fuel_TypeController::class, 'store'])->name('store');
            Route::get('/', [Fuel_TypeController::class, 'index'])->name('index');
        });

        //Engine Routes
        Route::prefix('/engine')->group(function(){
            Route::post('/', [EngineController::class, 'store'])->name('store');
            Route::get('/', [EngineController::class, 'index'])->name('index');
        });

        //Engine Size Routes
        Route::prefix('/engine-size')->group(function(){
            Route::post('/', [EngineSizeController::class, 'store'])->name('store');
            Route::get('/', [EngineSizeController::class, 'index'])->name('index');
        });

        //Engine Block Routes
        Route::prefix('/engine-bock')->group(function(){
            Route::post('/', [EngineBlockController::class, 'store'])->name('store');
            Route::get('/', [EngineBlockController::class, 'index'])->name('index');
        });

        //Door Routes
        Route::prefix('/door')->group(function(){
            Route::post('/', [DoorController::class, 'store'])->name('store');
            Route::get('/', [DoorController::class, 'index'])->name('index');
        });

        //Cylinder Routes
        Route::prefix('/cylinder')->group(function(){
            Route::post('/', [CylinderController::class, 'store'])->name('store');
            Route::get('/', [CylinderController::class, 'index'])->name('index');
        });

        //MAde In Routes
        Route::prefix('/made-in')->group(function(){
            Route::post('/', [MadeInController::class, 'store'])->name('store');
            Route::get('/', [MadeInController::class, 'index'])->name('index');
        });

        //Overall Height Routes
        Route::prefix('/overall-height')->group(function(){
        Route::post('/', [OverallHeightController::class, 'store'])->name('store');
            Route::get('/', [OverallHeightController::class, 'index'])->name('index');
        });

        //Overall Length Routes
        Route::prefix('/overall-length')->group(function(){
            Route::post('/', [OverallLengthController::class, 'store'])->name('store');
            Route::get('/', [OverallLengthController::class, 'index'])->name('index');
        });

        //Overall Width Routes
        Route::prefix('/overall-width')->group(function(){
            Route::post('/', [OverallWidthController::class, 'store'])->name('store');
            Route::get('/', [OverallWidthController::class, 'index'])->name('index');
        });

        //STD Seating Routes
        Route::prefix('/std-seating')->group(function(){
            Route::post('/', [StdSeatingController::class, 'store'])->name('store');
            Route::get('/', [StdSeatingController::class, 'index'])->name('index');
        });

        //Highway Mileage Model Routes
        Route::prefix('/highway-mpg')->group(function(){
            Route::post('/', [HighwayMpgController::class, 'store'])->name('store');
            Route::get('/', [HighwayMpgController::class, 'index'])->name('index');
        });

        //City Mileage Model Routes
        Route::prefix('/city-mpg')->group(function(){
            Route::post('/', [CityMpgController::class, 'store'])->name('store');
            Route::get('/', [CityMpgController::class, 'index'])->name('index');
        });


        //Car List Routes
        Route::prefix('/car-list')->group(function(){
            Route::post('/', [CarListController::class, 'store'])->name('store');
            Route::get('/', [CarListController::class, 'index'])->name('index');
            Route::get('/{id}', [CarListController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CarListController::class, 'update'])->name('update');
        });


        // Add to Cart
        Route::prefix('/cart')->group(function(){
            Route::get('/', [CartController::class, 'index']);
            Route::post('/', [CartController::class, 'add']);
            Route::post('/delete', [CartController::class, 'remove']);
        });

        // Checkout
        // Route::prefix(prefix: '/checkout')->group(function(){
        //     Route::get('/', function(){
        //         return "Hellow";
        //     });
        // });
        Route::get('checkout', [CheckoutController::class, 'checkout']);
        


        Route::prefix('/user')->group(function(){
            Route::get('/all-drop', [AllDropController::class, 'index'])->name('showAllDrop');
            Route::get('/profile', [SingleUserController::class, 'index'])->name('singleuser');
            Route::put('/profile',[SingleUserController::class, 'update'])->name('updateProfile');
        });


    });
});
