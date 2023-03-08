<?php

namespace App\Listeners\Orders;

use App\Events\OrderCreated;
use App\Jobs\OrderCreatedJob;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        logs()->info(self::class . ' has started');
        OrderCreatedJob::dispatch($event->order)->onQueue('emails');
    }
}
