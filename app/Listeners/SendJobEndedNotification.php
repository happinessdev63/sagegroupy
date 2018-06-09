<?php

namespace App\Listeners;

use App\Events\JobEndedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class SendJobEndedNotification
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
     * @param  JobEndedEvent  $event
     * @return void
     */
    public function handle(JobEndedEvent $event)
    {
        /* Create Two Notifications: 1 for the client, 1 for the freelancer */
        if ($event->agency instanceof \App\Agency) {

            /* Dont sent client notifications for reference jobs */
            if ($event->job->type != 'reference') {
                $event->client->notify( new \App\Notifications\ClientJobEnded( $event->client, $event->freelancer, $event->job ) );
            }

            $freelancerNotification = Notification::create( [
                'title' => 'Job Feedback Received - ' . $event->job->title,
                'message' => 'You\'ve completed a job and have recevied new feedback from the client.',
                'type'         => "job-completed",
                'agency_id'   => $event->agency->id,
                'from_agency_id'   => $event->agency->id,
                'from_user_id' => $event->client->id,
                'job_id'       => $event->job->id,
                'owner_id'     => $event->agency->id,
                'status'       => 'unread',
                'owner_type'   => 'agency'
            ] );

            /* Send freelaner & client notification emails */
            $event->agency->owner->notify( new \App\Notifications\AgencyJobEnded( $event->freelancer, $event->client, $event->job, $event->agency ) );


            return;
        }

        /* Dont sent client notifications for reference jobs */
        if ( $event->job->type != 'reference' ) {
            $event->client->notify( new \App\Notifications\ClientJobEnded( $event->client, $event->freelancer, $event->job ) );
        }

        $freelancerNotification = Notification::create( [
            'title'   => 'Job Feedback Received - ' . $event->job->title,
            'message' => 'You\'ve completed a job and have recevied new feedback from the client.',
            'type'         => "job-completed",
            'to_user_id'   => $event->freelancer->id,
            'from_user_id' => $event->client->id,
            'job_id'       => $event->job->id,
            'owner_id'     => $event->client->id,
            'status'       => 'unread',
            'owner_type'   => 'user'
        ] );

        /* Send freelaner & client notification emails */
        $event->freelancer->notify( new \App\Notifications\FreelancerJobEnded( $event->freelancer, $event->client, $event->job ) );

        return;

    }
}
