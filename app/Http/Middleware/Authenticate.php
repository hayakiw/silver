<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            if ($guard == 'admin') {
                return redirect()->guest('_admin/signin');
            }

            if ($guard == 'staff') {
                return redirect()->guest('staff/signin');
            }

            return redirect()->guest('signin');
        }

        return $next($request);
    }
}
