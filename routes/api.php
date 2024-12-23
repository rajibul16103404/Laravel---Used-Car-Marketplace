<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Body_Type\Controllers\Body_TypeController;
use Modules\Admin\CarLists\Controllers\CarListController;
use Modules\Admin\CarModel\Controllers\CarModelController;
use Modules\Admin\Category\Controllers\CategoryController;
use Modules\Admin\Color\Controllers\ColorController;
use Modules\Admin\Doors\Controllers\DoorController;
use Modules\Auth\Controllers\AuthController;
use Modules\Auth\Controllers\ForgotPasswordController;
use Modules\Auth\Controllers\ResetPasswordController;
use Modules\Admin\Users\Controllers\UserController;
use Modules\Admin\Condition\Controllers\ConditionController;
use Modules\Admin\Cylinders\Controllers\CylinderController;
use Modules\Admin\Drive_Type\Controllers\Drive_TypeController;
use Modules\Admin\Fuel_Type\Controllers\Fuel_TypeController;
use Modules\Admin\Make\Controllers\MakeController;
use Modules\Admin\Transmission\Controllers\TransmissionController;
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


// Webhook
Route::post('/webhook', [WhatsAppController::class, 'webhook']);

// Whatsapp

Route::get('/send', [WhatsappBotController::class, 'index'])->name('demo');





Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('role:admin')->group(function () {
        //Users List Routes
        Route::prefix('/admin/users-list')->group(function(){
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('single_view');
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

        //Category Routes
        Route::prefix('/admin/category')->group(function(){
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/{id}', [CategoryController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('delete');
        });

        //Color Routes
        Route::prefix('/admin/color')->group(function(){
            Route::post('/', [ColorController::class, 'store'])->name('store');
            Route::get('/', [ColorController::class, 'index'])->name('index');
            Route::get('/{id}', [ColorController::class, 'show'])->name('single_view');
            Route::put('/{id}', [ColorController::class, 'update'])->name('update');
            Route::delete('/{id}', [ColorController::class, 'destroy'])->name('delete');
        });

        //Condition Routes
        Route::prefix('/admin/condition')->group(function(){
            Route::post('/', [ConditionController::class, 'store'])->name('store');
            Route::get('/', [ConditionController::class, 'index'])->name('index');
            Route::get('/{id}', [ConditionController::class, 'show'])->name('single_view');
            Route::put('/{id}', [ConditionController::class, 'update'])->name('update');
            Route::delete('/{id}', [ConditionController::class, 'destroy'])->name('delete');
        });

        //Cylinder Routes
        Route::prefix('/admin/cylinder')->group(function(){
            Route::post('/', [CylinderController::class, 'store'])->name('store');
            Route::get('/', [CylinderController::class, 'index'])->name('index');
            Route::get('/{id}', [CylinderController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CylinderController::class, 'update'])->name('update');
            Route::delete('/{id}', [CylinderController::class, 'destroy'])->name('delete');
        });

        //Door Routes
        Route::prefix('/admin/door')->group(function(){
            Route::post('/', [DoorController::class, 'store'])->name('store');
            Route::get('/', [DoorController::class, 'index'])->name('index');
            Route::get('/{id}', [DoorController::class, 'show'])->name('single_view');
            Route::put('/{id}', [DoorController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoorController::class, 'destroy'])->name('delete');
        });

        //Drive_Type Routes
        Route::prefix('/admin/drive-type')->group(function(){
            Route::post('/', [Drive_TypeController::class, 'store'])->name('store');
            Route::get('/', [Drive_TypeController::class, 'index'])->name('index');
            Route::get('/{id}', [Drive_TypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [Drive_TypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [Drive_TypeController::class, 'destroy'])->name('delete');
        });

        //Fuel_Type Routes
        Route::prefix('/admin/fuel-type')->group(function(){
            Route::post('/', [Fuel_TypeController::class, 'store'])->name('store');
            Route::get('/', [Fuel_TypeController::class, 'index'])->name('index');
            Route::get('/{id}', [Fuel_TypeController::class, 'show'])->name('single_view');
            Route::put('/{id}', [Fuel_TypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [Fuel_TypeController::class, 'destroy'])->name('delete');
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

        //Transmisssion Routes
        Route::prefix('/admin/transmission')->group(function(){
            Route::post('/', [TransmissionController::class, 'store'])->name('store');
            Route::get('/', [TransmissionController::class, 'index'])->name('index');
            Route::get('/{id}', [TransmissionController::class, 'show'])->name('single_view');
            Route::put('/{id}', [TransmissionController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransmissionController::class, 'destroy'])->name('delete');
        });

        //Car List Routes
        Route::prefix('/admin/car-list')->group(function(){
            Route::post('/', [CarListController::class, 'store'])->name('store');
            Route::get('/', [CarListController::class, 'index'])->name('index');
            Route::get('/{id}', [CarListController::class, 'show'])->name('single_view');
            Route::put('/{id}', [CarListController::class, 'update'])->name('update');
            Route::delete('/{id}', [CarListController::class, 'destroy'])->name('delete');
        });


        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome, Admin']);
        });
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return response()->json(['message' => 'Welcome, User']);
        });
    });
});
