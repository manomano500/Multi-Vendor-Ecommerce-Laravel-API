<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Plutu\Services\PlutuAdfali;
use Plutu\Services\PlutuSadad;
use Plutu\Services\PlutuLocalBankCards;
use Plutu\Services\PlutuTlync;
use Plutu\Services\PlutuMpgs;

class PlutuServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PlutuAdfali::class, function ($app) {
            return new PlutuAdfali;
        });

        $this->app->singleton(PlutuSadad::class, function ($app) {
            return new PlutuSadad;
        });

        $this->app->singleton(PlutuLocalBankCards::class, function ($app) {
            return new PlutuLocalBankCards;
        });

        $this->app->singleton(PlutuTlync::class, function ($app) {
            return new PlutuTlync;
        });

        $this->app->singleton(PlutuMpgs::class, function ($app) {
            return new PlutuMpgs;
        });
    }
}
