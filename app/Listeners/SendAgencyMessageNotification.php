<?php

namespace App\Listeners;

use App\Events\AgencyMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAgencyMessageNotification
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
     * @param  AgencyMessageEvent  $event
     * @return void
     */
    public function handle(AgencyMessageEvent $event)
    {
        //
    }
}
