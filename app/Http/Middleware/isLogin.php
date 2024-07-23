<?php

namespace App\Http\Middleware;


use App\StaticString;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isLogin = $request->session()->exists(StaticString::SESSION_ISLOGIN);
        if ($isLogin) {
            return $next($request);
        } else {
            return redirect()->action('App\Http\Controllers\LoginController@getViewLogin');
        }
    }
}
