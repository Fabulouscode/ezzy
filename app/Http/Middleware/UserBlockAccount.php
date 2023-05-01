<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class UserBlockAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if(!empty(Auth::user()) && Auth::user()->status == 2) {
                return Response::json(['success' => 'false', 'message' => 'You have been deactivated please wait to be activated'], 422);
            }
        }
        return $next($request);
    }
}
