<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class TimezoneLocalization
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
        $ip = file_get_contents("http://ipecho.net/plain");
        $url = 'http://ip-api.com/json/'.$ip;
        $tz = file_get_contents($url);
        $tz = json_decode($tz,true)['timezone'];
        // $timezone = Carbon::now($tz);
        // set laravel localization
        config(['app.timezone' => $tz]);
        // app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
