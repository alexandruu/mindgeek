<?php

namespace App\Providers;

use App\Services\Http\HttpService;
use App\Services\Actors\Providers\PornhubActorsImport;
use App\Services\Http\RequestService;
use App\Services\Http\ResponseService;
use Illuminate\Support\ServiceProvider;

class HttpProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HttpService::class, function ($app) {
            $service = new HttpService($app->make(RequestService::class), $app->make(ResponseService::class));
            $service->registerProvider($app->make(PornhubActorsImport::class));
            return $service;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
