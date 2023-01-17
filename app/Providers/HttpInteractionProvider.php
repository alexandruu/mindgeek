<?php

namespace App\Providers;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\HttpStream;
use Illuminate\Support\ServiceProvider;

class HttpInteractionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HttpInteractionService::class, function ($app) {
            $service = new HttpInteractionService();
            $service->registerStrategy($app->make(HttpStream::class));
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
