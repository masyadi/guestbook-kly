<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::addNamespace('CMS', resource_path('views'.DIRECTORY_SEPARATOR.'cms'));
        
        View::addNamespace('WEB', resource_path('views'.DIRECTORY_SEPARATOR.'web'));
    }
}
