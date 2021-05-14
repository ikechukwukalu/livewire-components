<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\datatableEvent;
use App\Listeners\datatableCachePageListener;
use App\Listeners\datatableCacheLastPageListener;
// use App\Listeners\datatableCacheLastThreePagesListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    /***
     * Switch `datatableCacheLastPage` for `datatableCacheLastThreePage to cache the last 3 pages instead
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        datatableEvent::class => [
            datatableCachePageListener::class,
            datatableCacheLastPageListener::class,
            // datatableCacheLastThreePagesListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
