<?php

namespace App\Listeners;

use App\Events\JobReferenceRequestEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJobReferenceRequestNotification
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
    public function handle(JobReferenceRequestEvent $event)
    {
        $event->client->notify( new \App\Notifications\ClientReferenceRequested( $event->freelancer, $event->client, $event->job, $event->message ) );
    }
}
