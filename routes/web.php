<?php

use App\Http\Controllers\TargetScannedController;
use App\Http\Controllers\TrackingEndpointController;
use App\Http\Controllers\FuzzingEndpointController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\BurpSuiteController;
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

// Route::group('prefix' => '/auth', function () {

// });

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class => 'CheckLogin']);

Route::group(['middleware' => 'auth'], function () {
    // Route::group(['middleware' => 'author', 'prefix' => 'admin'], function () {
    //     Route::get('/add', function () {
    //         return view('addUser');
    //     });
    // });

    Route::get('/', function () {
        return view('index');
    })->name('index');

    Route::get('/network', function () {
        return view('network');
    });

    Route::get('/dirsearch', function (){
        return view('dirsearch');
    });
});

// Tracking Endpoint Routes
Route::get('/tracking-endpoints', [TrackingEndpointController::class, 'index']);
Route::post('tracking-endpoints', [TrackingEndpointController::class, 'requestToTarget']);

// Fuzzing Endpoint Routes
Route::get('/fuzzing', [FuzzingEndpointController::class, 'index']);
Route::post('/fuzzing', [FuzzingEndpointController::class, 'fuzz']);

// Target Scanned Routes
Route::get('/target-scanned', [TargetScannedController::class, 'index']);
Route::get('/target-scanned/{target_id}', [TargetScannedController::class,'show']);

// Burp Suite Connection Routes
Route::get('/burp', [BurpSuiteController::class,'burpProxyConnect']);

// Public listing reqresp routes
Route::get('/listing', [FuzzingEndpointController::class, 'reqrespListing']);
Route::get('/listing/{filename}', [FuzzingEndpointController::class, 'reqrespSpecificFilenameListing']);
Route::get('/listing/{hostname}', [FuzzingEndpointController::class, 'reqrespSpecificHostnameListing']);