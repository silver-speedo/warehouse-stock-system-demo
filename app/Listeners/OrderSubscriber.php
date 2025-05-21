<?php

namespace App\Listeners;

use App\Events\CreateOrderFailed;
use App\Events\CreateOrderSuccess;
use Illuminate\Support\Facades\Log;

class OrderSubscriber
{
    public function handleSuccessEvent(CreateOrderSuccess $event): void
    {
        //At this point you can create a notification object & use the various channels to push the success out
        //to the user, with an order success email, broadcasting to the front end for a toast notification or
        //maybe a database notification if using a pull system via an API call. For this example, it logs...
        Log::info($event->order);
    }

    public function handleFailureEvent(CreateOrderFailed $event): void
    {
        //At this point you can create a notification object & use the various channels to push the failure out
        //to the user, with an order failed email, broadcasting to the front end for a toast notification or
        //maybe a database notification if using a pull system via an API call. For this example, it logs...

        Log::info('Order failed message: ' . $event->message);
    }

    public function subscribe(): array
    {
        return [
            CreateOrderSuccess::class => 'handleSuccessEvent',
            CreateOrderFailed::class => 'handleFailureEvent',
        ];
    }
}
