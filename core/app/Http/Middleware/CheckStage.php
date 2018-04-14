<?php

namespace App\Http\Middleware;

use Closure;

class CheckStage
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

//        if ($request->stage <= 200) {
//            return redirect('stages');
//        }
        return $next($request);
    }
}
