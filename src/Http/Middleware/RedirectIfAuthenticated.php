<?php

namespace Palamike\Foundation\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!($request->ajax() || $request->wantsJson()) && Auth::guard($guard)->check()) {
            
            $user = Auth::user();
            $role = $user->roles->first();
            
            return redirect(!empty($role->redirect) ? $role->redirect : '/' );
        }

        return $next($request);
    }
}
