<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionExpirationMiddleware
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
            // Check if the session has expired
            $sessionExpirationTime = strtotime(session('expires_at'));
            $currentTime = now()->timestamp;

            if ($currentTime > $sessionExpirationTime) {
                // Session has expired, redirect to login page
//                Auth::logout();
//                return redirect('/login');
            }
        }

        return $next($request);
    }
}
