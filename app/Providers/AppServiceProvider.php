<?php

namespace App\Providers;

use App\Interfaces\Actors;
use App\Interfaces\ActorsApi;
use App\Services\PornhubActors;
use App\Services\PornhubActorsApi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ActorsApi::class => PornhubActorsApi::class,
        Actors::class => PornhubActors::class
    ];
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
        //
    }
}
