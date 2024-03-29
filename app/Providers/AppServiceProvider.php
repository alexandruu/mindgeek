<?php

namespace App\Providers;

use App\Interfaces\CacheInterface;
use App\Interfaces\StorageInterface;
use App\Services\Cache\CacheService;
use App\Services\Storage\StorageService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        StorageInterface::class => StorageService::class,
        CacheInterface::class => CacheService::class,
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
