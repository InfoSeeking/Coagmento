<?php

namespace App\Http\Middleware;

use Closure;

class MustBeAdministrator
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

        $user = $request->user();

        if($user && $user->is_admin || $user->active) {
            return $next($request);
        }
        //What about study participiant (active users)

        abort(404, 'You are unable to access this page.');
    }

}
