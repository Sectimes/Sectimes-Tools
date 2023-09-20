<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function CheckLogin(Request $request) {
        dd($request);
        if ($request->username === "d7cky" && $request->password === "123") {
            return view('index', $request->username);
        }
        return "Sai tài khoản hoặc mật khẩu nhá";
    }
}
