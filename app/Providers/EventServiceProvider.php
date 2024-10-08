<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderProductUpdated;
use App\Listeners\ProcessOrderPayment;
use App\Listeners\SendNewUserNotification;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\UpdateOrderStatus;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     *
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendNewUserNotification::class,
        ],
        OrderCreated::class => [
            ProcessOrderPayment::class,

            SendOrderCreatedNotification::class,
        ],
        OrderProductUpdated::class => [
            UpdateOrderStatus::class,
        ],
        ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
