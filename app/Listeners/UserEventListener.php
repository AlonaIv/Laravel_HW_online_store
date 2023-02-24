<?php

namespace App\Listeners;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class UserEventListener
{
    protected $instances = ['cart', 'wishlist'];

    public function handleLogin($event)
    {
        collect($this->instances)->each(function ($instance) use ($event) {
            Cart::instance($instance)->restore($event->user->id);
        });
    }

    public function handleLogout($event)
    {
        collect($this->instances)->each(function ($instance) use ($event) {
            if (Cart::instance($instance)->count() > 0) {
                Cart::instance($instance)->store($event->user->id);
            }
        });

    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [UserEventListener::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [UserEventListener::class, 'handleLogout']
        );
    }
}
