<?php

namespace App\Providers;

use App\Interfaces\StorageInterface;
use App\Services\Http\Responses\StreamResponse;
use Illuminate\Support\ServiceProvider;

class StreamResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StreamResponse::class, function ($app) {
            $storageService = $app->make(StorageInterface::class);
            $service = new StreamResponse($storageService);
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
