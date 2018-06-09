<?php

namespace App\Listeners;

use App\Events\AgencyJobInviteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class SendAgencyJobInviteNotification
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
     * @param  jobInviteEvent $event
     * @return void
     * @todo - Send job invite email notification
     */
    public function handle( AgencyJobInviteEvent $event )
    {
        /* Create Two Notifications: 1 for the job, 1 for the invited user */
        $jobNotification = Notification::create( [
            'type'           => "job-invite",
            'from_agency_id' => $event->agency->id,
            'to_user_id'     => $event->client->id,
            'job_id'         => $event->job->id,
            'from_user_id'   => $event->client->id,
            'owner_id'       => $event->client->id,
            'status'         => 'read',
            'title'          => "Invite Sent To Agency: " . $event->agency->name,
            'message'        => "You invited this user to your job. You will be notified when they accept or decline the invitation.",
            'owner_type'     => 'user'
        ] );

        $userNotification = Notification::create( [
            'type'           => "job-invite",
            'owner_type'     => 'agency',
            'agency_id'      => $event->agency->id,
            'from_agency_id' => $event->agency->id,
            'owner_id'       => $event->agency->id,
            'job_id'         => $event->job->id,
            'from_user_id'   => $event->client->id,
            'status'         => 'unread',
            'title'          => "Your Agency Has Been Invited to A Job - \"" . $event->job->title . "\"",
            'message'        => "You were invited to apply for this job. Please either accept or decline this invitation as soon as possible. \n\n Message from client: " . $event->message,
        ] );

        /* Create new invite for the job */
        $event->job->invites()->create([
            'client_id'     => $event->client->id,
            'agency_id' => $event->agency->id,
            'status'        => 'pending'
        ]);

        /* Send the notification email */
        $event->agency->owner->notify( new \App\Notifications\AgencyInvitedToJob( $event->agency->owner, $event->client, $event->job, $event->agency,$event->message ) );
    }
}
