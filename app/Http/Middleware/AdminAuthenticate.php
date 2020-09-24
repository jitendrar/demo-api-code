<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Closure;

class AdminAuthenticate
{
    protected $auth;
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $guard = "admins";
        if (!Auth::guard($guard)->check()) 
        {
            if ($request->ajax()) 
            {
                return response('Unauthorized.', 401);
            } 
            else 
            {
                return redirect()->guest('login');
            }
        }
        return $next($request);
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                                        ->header('Pragma','no-cache');
    }
}
