<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
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

Route::post('/login', [LoginController::class, 'CheckLogin']);

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'author', 'prefix' => 'admin'], function () {
        Route::get('/add', function () {
            return view('addUser');
        });
    });

    Route::get('/', function () {
        return view('index');
    });

    Route::get('/nmap', function () {
        return view('nmap');
    });

    Route::get('/dirsearch', function (){
        return view('dirsearch');
    });
});

