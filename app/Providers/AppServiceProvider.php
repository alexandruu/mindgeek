<?php

namespace App\Providers;



use App\Interfaces\ActorsInterface;
use App\Interfaces\StorageInterface;
use App\Services\PornhubActors;
use App\Services\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ActorsInterface::class => PornhubActors::class,
        StorageInterface::class => Storage::class
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
        Paginator::useBootstrapFive();
    }
}
