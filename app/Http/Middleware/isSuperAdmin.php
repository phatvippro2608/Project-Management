<?php

namespace App\Http\Middleware;

use App\StaticString;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isSuperAdmin = $request->session()->get(StaticString::PERMISSION)==1;
        if ($isSuperAdmin) {
            return $next($request);
        }
        return redirect('/');
    }
}
