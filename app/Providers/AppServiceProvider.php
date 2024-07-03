<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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

        $conexionSoftlink='softtest'; // default
        if(config('app.env')=='production'){
            $conexionSoftlink='soft2';
        }

        app()->instance('conexion_softlink',$conexionSoftlink);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // Paginator::defaultView()
        Paginator::useBootstrap();
    }
}
