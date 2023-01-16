<?php

namespace App\Providers;

use App\Services\ActorsImport;
use App\Services\PornhubActorsImport;
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
            $service->registerStrategy($app->make(PornhubActorsImport::class));
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
