<?php

namespace App\Providers\Backend;

use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
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
        $this->app->singleton('userrepository', function($app){
            return new \App\Repositories\Backend\UserRepository($app);
        });
    }
}
