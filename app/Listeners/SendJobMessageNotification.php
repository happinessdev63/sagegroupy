<?php

namespace App\Listeners;

use App\Events\JobMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJobMessageNotification
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
     * @param  JobMessageEvent  $event
     * @return void
     */
    public function handle(JobMessageEvent $event)
    {
        //$event->receiver->notify( new \App\Notifications\UserReceivedMessage( $event->receiver, $event->sender, $event->job, $event->message ) );
    }
}
