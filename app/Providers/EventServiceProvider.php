<?php

namespace App\Providers;

use App\Events\PostCreated;
use App\Events\TaskCreated;
use App\Listeners\LogKeyForgotten;
use App\Listeners\LogKeyWritten;
use App\Listeners\SendPostCreatedNotification;
use App\Listeners\SendTaskCreatedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskCreated::class => [
            SendTaskCreatedNotification::class,
        ],
        PostCreated::class => [
          SendPostCreatedNotification::class,
        ],
        KeyForgotten::class => [
            LogKeyForgotten::class,
        ],

        KeyWritten::class => [
            LogKeyWritten::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
