<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admins')->check()) 
        {
            return redirect()->route('admin-dashboard');
        }
        return $next($request);
    }
}
