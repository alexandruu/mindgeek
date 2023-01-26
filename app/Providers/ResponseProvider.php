<?php

namespace App\Providers;

use App\Services\Import\Responses\StreamResponse;
use App\Services\Import\ResponseService;
use Illuminate\Support\ServiceProvider;

class ResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResponseService::class, function ($app) {
            $service = new ResponseService();
            $service->addResponse($app->make(StreamResponse::class));
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
