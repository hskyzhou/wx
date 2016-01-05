<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*后台*/
        view()->composer(['admin.header', 'admin.layout'], \App\ViewComposers\Backend\HeaderComposer::class);
        view()->composer('admin.layout', \App\ViewComposers\Backend\BreadcrumbComposer::class);
        view()->composer('admin.layout', \App\ViewComposers\Backend\MenuComposer::class);
        view()->composer(['admin.layout', 'admin.header'], \App\ViewComposers\Backend\LayoutComposer::class);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
