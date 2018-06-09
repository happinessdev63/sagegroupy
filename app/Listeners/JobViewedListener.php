<?php

namespace App\Listeners;

use App\Events\JobViewedEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobViewedListener
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
     * @param  JobViewedEvent  $event
     * @return void
     */
    public function handle( JobViewedEvent $event )
    {

        $viewParams = [
            'type' => 'anon',
            'source' => $event->source,
            'view_type' => $event->type,
        ];

        if ( $event->viewer instanceof User ) {
            $viewParams['type'] = 'user';
            $viewParams['viewer_id'] = $event->viewer->id;
        }

        $event->job->views()->create( $viewParams );
    }
}
