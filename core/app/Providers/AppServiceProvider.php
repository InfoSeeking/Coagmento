<?php

namespace App\Providers;

use App\Services\ProjectService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // TODO: It may be more Laravel-esque to make separate interface classes.
        $this->app->singleton('App\Services\ProjectService', 'App\Services\ProjectService', true);
        $this->app->singleton('App\Services\BookmarkService', 'App\Services\BookmarkService', true);
        $this->app->singleton('App\Services\MembershipService', 'App\Services\MembershipService', true);
        $this->app->singleton('App\Services\TagService', 'App\Services\TagService', true);
        $this->app->singleton('App\Services\SnippetService', 'App\Services\SnippetService', true);
        $this->app->singleton('App\Services\QueryService', 'App\Services\QueryService', true);
    }
}
