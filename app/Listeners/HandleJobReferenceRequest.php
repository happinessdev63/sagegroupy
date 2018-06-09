<?php

namespace App\Listeners;

use App\Agency;
use App\Events\JobReferenceRequestEvent;
use App\Job;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class HandleJobReferenceRequest
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  AgencyInviteResponseEvent  $event
     * @return void
     * @todo - Send job invite emails
     */
    public function handle(JobReferenceRequestEvent $event)
    {

        /* Send invite response email */
        $event->client->notify( new \App\Notifications\UserRespondedToJobInvite( $event->client, $event->freelancer, $event->job, $event->response ) );

    }
}
