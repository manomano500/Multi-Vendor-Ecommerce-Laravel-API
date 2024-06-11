<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{



    protected  $listen = [
        'App\Events\OrderCreated' => [
            'App\Listeners\SendOrderCreatedNotification',
        ],
    ];




    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
