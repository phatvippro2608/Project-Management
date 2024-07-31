<?php

namespace App\Http\Middleware;

use App\StaticString;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isHeadOfDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isHead = $request->session()->get(StaticString::PERMISSION)==3;
        if ($isHead) {
            return $next($request);
        }
        return redirect('/');
    }
}
