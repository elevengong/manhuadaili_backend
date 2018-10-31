<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class DailiLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->session()->has('daili_name'))
        {
            return redirect('/');
        }
        return $next($request);

    }
}
