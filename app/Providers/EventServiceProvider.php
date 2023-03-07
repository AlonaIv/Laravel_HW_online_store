<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\Orders\CreatedListener;
use App\Listeners\UserEventListener;
use App\Models\Image;
use App\Models\Product;
use App\Observers\ImageObserver;
use App\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            CreatedListener::class
        ],
    ];

    protected $observers = [
        Image::class => [ImageObserver::class],
        Product::class => [ProductObserver::class]
    ];

    protected $subscribe = [
        UserEventListener::class,
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
