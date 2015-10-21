<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class AfterAuthMiddleware
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
        $current_user = Auth::user();
        
        view()->share('login_name', $current_user->name);
        view()->share('login_avatar', asset('custom/images/default.jpg'));

        return $next($request);
    }
}
