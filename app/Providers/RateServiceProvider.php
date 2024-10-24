<?php

namespace App\Providers;

use App\Services\RateService;
use Illuminate\Support\ServiceProvider;

class RateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Enregistrement de RateService dans le conteneur en tant que singleton
        $this->app->singleton(RateService::class, function ($app) {
            // Récupération de l'URL à partir du fichier .env
            $url = config('services.rate.url');

            return new RateService($url);
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
