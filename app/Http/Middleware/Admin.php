<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class Admin
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
        if (Auth::check()) {
            if (Auth::user()->administrador == true) {
                return $next($request);
            } elseif (Auth::user()->administrador == false) {
                return back();
            }
        }
        return redirect()->route('login');
    }
}
