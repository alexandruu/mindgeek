<?php

namespace App\Providers;

use App\Services\Http\Responses\SimpleResponse;
use App\Services\Http\Responses\StreamResponse;
use App\Services\Http\ResponseService;
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
            $service->addResponse($app->make(SimpleResponse::class));
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
