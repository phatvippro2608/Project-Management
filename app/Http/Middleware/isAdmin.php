<?php

namespace App\Http\Middleware;

use App\StaticString;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = $request->session()->get(StaticString::PERMISSION) == 2 || $request->session()->get(StaticString::PERMISSION) == 1;
        if ($isAdmin) {
            return $next($request);
        }
        return redirect('/');
    }
}
