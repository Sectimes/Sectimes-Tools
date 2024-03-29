<?php

use App\Http\Controllers\OneClickScanController;
use App\Http\Controllers\TargetScannedController;
use App\Http\Controllers\TrackingEndpointController;
use App\Http\Controllers\FuzzingEndpointController;
use App\Http\Controllers\UpdateChartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingRequestResponseController;
use App\Http\Controllers\ListingFfufResultController;
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

// Route::post('/login', [LoginController::class => 'CheckLogin']);

// Route::group(['middleware' => 'auth'], function () {
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
// });

// Tracking Endpoint Routes
Route::get('/tracking-endpoints', [TrackingEndpointController::class, 'index']);
Route::post('tracking-endpoints', [TrackingEndpointController::class, 'requestToTarget']);

// Fuzzing Endpoint Routes
Route::get('/fuzzing', [FuzzingEndpointController::class, 'index']);
Route::post('/fuzzing', [FuzzingEndpointController::class, 'fuzz']);

// Check Job is done or not
Route::get('/check-job-status/{jobName}', [FuzzingEndpointController::class,'checkJobStatus']);  

// Target Scanned Routes
Route::get('/target-scanned', [TargetScannedController::class, 'index']);
Route::get('/target-scanned/{target_id}', [TargetScannedController::class,'show']);

// Burp Suite Connection Routes
Route::post('/burp', [BurpSuiteController::class, 'burpProxyConnect']);

// Public listing reqresp routes
Route::get('/listing', [ListingRequestResponseController::class, 'reqrespListing']);
Route::get('/listing/{hostOrFilename}', [ListingRequestResponseController::class, 'reqrespSpecificHostnameListing']);
Route::get('/listing/{hostname}/{filename}', [ListingRequestResponseController::class, 'reqrespSpecificFilenameListing']); 

// Public result-ffuf dir routes
Route::get('/ffuf-result', [ListingFfufResultController::class, 'resultFfufListing']);
Route::get('/ffuf-result/{filename}', [ListingFfufResultController::class, 'resultFfufSpecificFilenameListing']);

// Heart Letter route
Route::get('/heart-letter', function (){
    return view('heart-letter');
});

// 1 Click Scan route
Route::get('/scan', [OneClickScanController::class, 'index']);
Route::post('/scan', [OneClickScanController::class, 'scan']);