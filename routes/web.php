<?php

use App\Http\Controllers\ComposerController;
use App\Http\Controllers\PrivatCarController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\WebhooController;

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

Route::get('/', [PrivatCarController::class, 'index']);

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

// Clear Config
Route::get('/clear_config', function() {
    Artisan::call('config:clear');
    return 'Config Cleared successfully!';
});

// Clear Cache
Route::get('/clear_cache', function() {
    Artisan::call('cache:clear');
    return 'Cache Cleared successfully!';
});

// Truncate Table


Route::get('/truncate-table', function () {
    DB::table('auths')->truncate();
    return response()->json(['message' => 'Table truncated successfully!']);
});

//Drop all tables

Route::get('/drop-all-tables', function () {
    try {
        // Disable foreign key checks to avoid constraint errors
        Schema::disableForeignKeyConstraints();

        // Fetch all table names (for MySQL)
        $tables = DB::select('SHOW TABLES');

        // Drop each table
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . env('DB_DATABASE')}; // MySQL result format
            Schema::dropIfExists($tableName);
        }

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

        return response()->json(['message' => 'All tables dropped successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error dropping tables: ' . $e->getMessage()], 500);
    }
});

//Drop password_reset Table

Route::get('/drop-table/{table}', function ($table) {
    try {
        // Drop the table if it exists
        if (Schema::hasTable($table)) {
            Schema::dropIfExists($table);
            return response()->json(['message' => "Table '{$table}' dropped successfully."]);
        } else {
            return response()->json(['message' => "Table '{$table}' does not exist."]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error dropping table: ' . $e->getMessage()], 500);
    }
});



// install whatsapp
Route::get('/composer-require-whatsapp', [ComposerController::class, 'composerRequireWhatsapp'])->middleware('auth'); // Ensure authentication





// webhook
