<?php

namespace App\Listeners;

use App\Events\JobInviteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class SendJobInviteNotification
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
    public function handle( JobInviteEvent $event )
    {
        /* Create Two Notifications: 1 for the job, 1 for the invited user */
        $jobNotification = Notification::create( [
            'type'           => "job-invite",
            'to_user_id'     => $event->client->id,
            'job_id'         => $event->job->id,
            'from_user_id'   => $event->client->id,
            'owner_id'       => $event->client->id,
            'status'         => 'read',
            'title'          => "Invite Sent To " . $event->freelancer->name,
            'message'        => "You invited this user to your job. You will be notified when they accept or decline the invitation.",
            'owner_type'     => 'user'
        ] );

        $userNotification = Notification::create( [
            'type'           => "job-invite",
            'to_user_id'     => $event->freelancer->id,
            'job_id'         => $event->job->id,
            'from_user_id'   => $event->client->id,
            'owner_id'       => $event->freelancer->id,
            'status'         => 'unread',
            'title'          => "You've been invited to this job - \"" . $event->job->title . "\"",
            'message'        => "You were invited to apply for this job. Please either accept or decline this invitation as soon as possible. \n\n Message from client: " . $event->message,
            'owner_type'     => 'user'
        ] );

        /* Create new invite for the job */
        $event->job->invites()->create([
            'client_id'     => $event->client->id,
            'freelancer_id' => $event->freelancer->id,
            'status'        => 'pending'
        ]);

        /* Send the notification email */
        $event->freelancer->notify( new \App\Notifications\UserInvitedToJob( $event->freelancer, $event->client, $event->job, $event->message ) );
    }
}
