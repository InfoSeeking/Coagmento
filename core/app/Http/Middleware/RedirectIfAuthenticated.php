<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            //return redirect('/stages');
            //dd("this:", $this->user);
            if($this->auth->user()->is_admin == 1){
                return redirect()->action('AdminController@index');
            }
            return redirect()->action(
                'ParticipantController@start', ['id' => 4]
            ); //hardcoded value for demo
        }
//        if ($this->auth->check()) {
//            return redirect('/workspace');
//        }

        return $next($request);
    }
}
