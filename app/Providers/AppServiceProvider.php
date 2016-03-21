<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SpecialService;
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
        $this->app->singleton(\App\Services\Contracts\WxReceiveNormalContract::class, \App\Services\WxReceiveNormalService::class);
        $this->app->singleton(\App\Services\Contracts\WxReceiveTextContract::class, \App\Services\WxReceiveTextService::class);
    }
}
