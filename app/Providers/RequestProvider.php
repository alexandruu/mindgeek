<?php

namespace App\Providers;

use App\Services\Import\Requests\StreamRequest;
use App\Services\Import\RequestService;
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
