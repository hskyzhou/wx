<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionTreeProvider extends ServiceProvider
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
        $this->app->bind(\App\Services\Contracts\PermissionContract::class, \App\Services\PermissionService::class);
    }
}
