<?php

namespace App\Providers;

use App\Services\Import\ImportService;
use App\Services\Import\Providers\PornhubActorsImport;
use App\Services\Import\RequestService;
use App\Services\Import\ResponseService;
use Illuminate\Support\ServiceProvider;

class ImportProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ImportService::class, function ($app) {
            $service = new ImportService($app->make(RequestService::class), $app->make(ResponseService::class));
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
