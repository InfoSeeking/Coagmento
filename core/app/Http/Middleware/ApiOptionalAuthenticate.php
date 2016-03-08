<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Utilities\Status;
use App\Utilities\StatusCodes;
use App\Utilities\ApiResponse;

class ApiOptionalAuthenticate
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
        if (Auth::check()) return $next($request);
        // onceBasic returns null if success.
        if (Auth::onceBasic() == null) return $next($request);

        // (Deprecated) Check for additional email and password parameters.
        // Recommend using basic auth for consistency.
        if ($request->has('auth_email') && $request->has('auth_password')) {
            $args = [
                'email' => $request->input('auth_email'),
                'password' => $request->input('auth_password')
                ];
            if (!Auth::attempt($args)) {
                return ApiResponse::fromStatus(
                    Status::fromError('Incorrect credentials',
                    StatusCodes::UNAUTHENTICATED));
            }
        }

        // Regardless of whether user logs in or not, proceed.
        return $next($request);
    }
}
