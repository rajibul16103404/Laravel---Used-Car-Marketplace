<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('blog')->group(function () {
    Route::get('/', [\Modules\Blog\Controllers\BlogController::class, 'index']);
});

Route::prefix('auth')->group(function () {
    Route::get('/', [\Modules\Auth\Controllers\AuthController::class, 'index']);
});



// Migration route
Route::get('/run-migrations', function() {
    Artisan::call('migrate');
    return 'Migrations ran successfully!';
});

// Seed Database
Route::get('/db_seed', function() {
    Artisan::call('db:seed');
    return 'Seeding ran successfully!';
});

// Truncate Table
use Illuminate\Support\Facades\DB;

Route::get('/truncate-table', function () {
    DB::table('auths')->truncate();
    return response()->json(['message' => 'Table truncated successfully!']);
});

