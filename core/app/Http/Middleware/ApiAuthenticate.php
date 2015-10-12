<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Closure;

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
        if (!Auth::check()) {
            //return ApiError::make("Not authenticated, either use a browser session or pass encrypted.");
            $content = [
                "error" => [
                    "messages" => [
                            "Not authenticated. Either use a session or pass email/password"
                        ]
                    ]
                ];
            return (new Response($content, 401))->header("Content-Type", "application/json");
        }
        // TODO: For non-browser uses of the API, implement a stateless security based on
        // http://talks.codegram.com/http-authentication-methods

        return $next($request);
    }
}
