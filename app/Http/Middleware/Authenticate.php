<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     $checklogin = $this->isLogin($request);
    //     if ($request->path() === "login" && $checklogin === false) {
    //         return redirect(route('login'));
    //     } else {
    //         return response($checklogin);
    //     }

    //     if ($request->header("token") !== $checklogin) {
    //         return redirect(route('login'));
    //     } 
    // }

    // public function isLogin($request) {
    //     $token = md5(uniqid(rand(), true));
    //     if ($request->input('username') === "d7cky" && $request->input('password') === "123") {
    //         return $token;
    //     }
    //     return false;
    // }
}
