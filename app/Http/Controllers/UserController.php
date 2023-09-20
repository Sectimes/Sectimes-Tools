<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class UserController extends Controller
{
    public function index() {
        return view('user');
    }
}
