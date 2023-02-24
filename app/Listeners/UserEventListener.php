<?php

namespace App\Listeners;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class UserEventListener
{
    public function handleLogin($event)
    {
        Cart::instance('cart')->restore($event->user->id);
    }

    public function handleLogout($event)
    {
        if (Cart::instance('cart')->count() > 0) {
            Cart::instance('cart')->store($event->user->id);
        }
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
