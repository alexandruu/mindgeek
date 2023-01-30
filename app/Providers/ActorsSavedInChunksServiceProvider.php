<?php

namespace App\Providers;

use App\Services\Actors\ActorsSavedInChuncksService;
use Illuminate\Support\ServiceProvider;

class ActorsSavedInChunksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActorsSavedInChuncksService::class, function ($app) {
            return new ActorsSavedInChuncksService();
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
