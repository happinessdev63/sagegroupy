<?php

namespace App\Listeners;

use App\Events\ProfileViewedEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfileViewedListener
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
     * @param  ProfileViewedEvent  $event
     * @return void
     */
    public function handle(ProfileViewedEvent $event)
    {

        $viewParams = [
            'type' => 'anon',
            'source' => $event->source,
            'view_type' => $event->type,
        ];

        if ($event->viewer instanceof User) {

            /* Dont record views on users own profile */
            if ( $event->viewer->id == $event->profile->id ) {
                return;
            }

            $viewParams['type'] = 'user';
            $viewParams['viewer_id'] = $event->viewer->id;
        }

        $event->profile->views()->create($viewParams);
    }
}
