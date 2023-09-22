<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function CheckLogin(Request $request) {
        dd($request->all());
        // if ($request->input('username') === "d7cky" && $request->input('password') === "123") {
        //     return view('index');
        // }
        // return view('login');
    }
}
