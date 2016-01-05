<?php

namespace App\Providers\Backend;

use Illuminate\Support\ServiceProvider;

class RoleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('rolerepository', function($app){
            return new \App\Repositories\Backend\RoleRepository($app);
        });
    }
}
