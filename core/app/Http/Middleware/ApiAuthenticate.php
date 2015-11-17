<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Utilities\Status;
use App\Utilities\StatusCodes;
use App\Utilities\ApiResponse;

class ApiAuthenticate
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
        // TODO: For non-browser uses of the API, implement a stateless security based on
        // http://talks.codegram.com/http-authentication-methods
        if (!Auth::check()) {
            if ($request->has('auth_email') && $request->has('auth_password')) {
                $args = [
                    'email' => $request->input('auth_email'),
                    'password' => $request->input('auth_password')
                    ];
                if (!Auth::attempt($args)) {
                    return ApiResponse::fromStatus(
                        Status::fromError('Incorrect credentials',
                        StatusCodes::UNAUTHENTICATED));
                } else {
                    return $next($request);
                }
            }

            return ApiResponse::fromStatus(
                Status::fromError('Not authenticated',
                StatusCodes::UNAUTHENTICATED));
        }
        return $next($request);
    }
}
