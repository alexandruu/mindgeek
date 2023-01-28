<?php

namespace App\Providers;

use App\Services\Import\Savers\ActorsSaver;
use App\Services\Import\SaverService;
use Illuminate\Support\ServiceProvider;

class SaverProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SaverService::class, function ($app) {
            $service = new SaverService();
            $service->addSaver($app->make(ActorsSaver::class));
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
