<?php

namespace App\Listeners;

use App\Events\GeneralReferenceRequestEvent;
use App\GeneralReference;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendGeneralReferenceRequestNotification
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
     * @param  JobReferenceRequestEvent  $event
     * @return void
     */
    public function handle(GeneralReferenceRequestEvent $event)
    {
        $event->client->notify( new \App\Notifications\GeneralReferenceRequested($event->reference,  $event->client, $event->freelancer, $event->message ) );
    }
}
