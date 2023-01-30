<?php

namespace App\Providers;

use App\Services\Http\Requests\SimpleRequest;
use App\Services\Http\Requests\StreamRequest;
use App\Services\Http\RequestService;
use Illuminate\Support\ServiceProvider;

class RequestProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RequestService::class, function ($app) {
            $service = new RequestService();
            $service->addRequest($app->make(StreamRequest::class));
            $service->addRequest($app->make(SimpleRequest::class));
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
