<?php

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
    return view('index');
});

Route::get('/user', function () {
    return view('user');
});

Route::get('/user/add', function () {
    return view('addUser');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/nmap', function () {
    return view('nmap');
});