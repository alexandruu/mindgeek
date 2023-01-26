<?php

namespace App\Providers;

use App\Normalizers\Providers\PornhubResponseNormalizer;
use App\Services\Import\NormalizeResponseService;
use Illuminate\Support\ServiceProvider;

class NormalizeResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NormalizeResponseService::class, function ($app) {
            $service = new NormalizeResponseService();
            $service->addNormalizer($app->make(PornhubResponseNormalizer::class));
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
