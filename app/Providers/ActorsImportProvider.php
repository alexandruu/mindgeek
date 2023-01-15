<?php

namespace App\Providers;

use App\Services\ActorsImport;
use App\Services\PornhubActorsApi;
use Illuminate\Support\ServiceProvider;

class ActorsImportProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActorsImport::class, function ($app) {
            $service = new ActorsImport();
            $service->registerStrategy($app->make(PornhubActorsApi::class));
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
