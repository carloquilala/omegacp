<?php

namespace artworx\omegacp\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use artworx\omegacp\Facades\Omega;

class OmegaAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $user = Omega::model('User')->find(Auth::id());

            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        return redirect(route('omega.login'));
    }
}
