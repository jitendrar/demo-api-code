<?php

namespace App\Http\Middleware;

use Closure;

class ApiLanguageSwitcher
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
        $langauge = \Request::header('language');

        \App::setLocale(!empty($langauge) ? $langauge : \Config::get('app.locale'));

        return $next($request);
    }
}
