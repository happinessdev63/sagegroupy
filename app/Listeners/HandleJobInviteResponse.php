<?php

namespace App\Listeners;

use App\Agency;
use App\Job;
use App\Events\JobInviteResponseEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class HandleJobInviteResponse
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
    public function handle(JobInviteResponseEvent $event)
    {
        $inviteStatus = "new";

        if ($event->agencyInvite) {

            /* Load invite and agency info */
            $agency = \App\Agency::where( 'id', $event->agencyId )->first();
            if ( !$agency ) {
                return;
            }

            $invites = \App\Invite::where( "client_id", $event->client->id )->where( "agency_id", $event->agencyId )->where( "job_id", $event->job->id )->get();

            switch ($event->response) {
                case "accept":

                    $inviteStatus = "accepted";

                    if ( !$event->job->agency_id ) {
                        $event->job->agency_id = $agency->id;
                        $event->job->save();
                        $message = $agency->name . " accepted your job invite. The agency has now been assigned as to your job.";
                    } else {
                        $message = $agency->name . " accepted your job invite, however you already have an active agency assigned to the job. The job cannot be reassigned unless the active agency is removed.";
                    }

                    $jobNotification = Notification::create( [
                        'type'         => "job-invite",
                        'to_user_id'   => $event->client->id,
                        'job_id'       => $event->job->id,
                        'from_user_id' => $event->client->id,
                        'owner_id'     => $event->client->id,
                        'status'       => 'unread',
                        'title'        => $agency->name . " Accepted Your Job Invite",
                        'message'      => $message,
                        'owner_type'   => 'user'
                    ] );

                    break;

                case "reject":

                    $inviteStatus = "rejected";

                    $jobNotification = Notification::create( [
                        'type'         => "job-invite",
                        'to_user_id'   => $event->client->id,
                        'job_id'       => $event->job->id,
                        'from_user_id' => $event->client->id,
                        'owner_id'     => $event->client->id,
                        'status'       => 'unread',
                        'title'        => $agency->name . " Rejected Your Job Invite",
                        'message'      => $agency->name . " rejected your invitation to your job. Try inviting more agencies or freelancers.",
                        'owner_type'   => 'user'
                    ] );

                    break;

            }

            /* Send invite response email */
            $event->client->notify( new \App\Notifications\AgencyRespondedToJobInvite( $event->client, $event->freelancer, $agency, $event->job, $event->response ) );

            /* Update any related invitiations */
            foreach ($invites as $invite) {
                $invite->status = $inviteStatus;
                $invite->save();
            }

            return;
        }

        /* Standard Job Invite */
        switch ($event->response) {
            case "accept":

                $inviteStatus = "applied";

                if( !$event->job->freelancer_id ){
                    $message = $event->freelancer->first_name . " accepted your job invite.";
                } else {
                    $message = $event->freelancer->first_name . " accepted your job invite, however you already have an active freelancer assigned to the job. The job cannot be reassigned unless the active freelancer is removed.";
                }

                $jobNotification = Notification::create( [
                    'type'         => "job-invite",
                    'to_user_id'   => $event->client->id,
                    'job_id'       => $event->job->id,
                    'from_user_id' => $event->client->id,
                    'owner_id'     => $event->client->id,
                    'status'       => 'unread',
                    'title'        => $event->freelancer->name . " Accepted Your Job Invite",
                    'message'      => $message,
                    'owner_type'   => 'user'
                ] );

                break;

            case "award":

                $inviteStatus = "accepted";
            // if ( !$event->job->freelancer_id ) {
                $event->job->freelancer_id = $event->freelancer->id;
                $event->job->public = 0;
                $event->job->save();
                $message = "This job was just awarded to you";

                $jobNotification = Notification::create( [
                    'type'         => "job-invite",
                    'to_user_id'   => $event->freelancer->id,
                    'job_id'       => $event->job->id,
                    'from_user_id' => $event->client->id,
                    'owner_id'     => $event->freelancer->id,
                    'status'       => 'unread',
                    'title'        => "Offer from ".$event->client->name,
                    'message'      => $message,
                    'owner_type'   => 'user'
                ] );

                break;

            case "reject":

                $inviteStatus = "rejected";

                $jobNotification = Notification::create( [
                    'type'         => "job-invite",
                    'to_user_id'   => $event->freelancer->id,
                    'job_id'       => $event->job->id,
                    'from_user_id' => $event->client->id,
                    'owner_id'     => $event->client->id,
                    'status'       => 'unread',
                    'title'        => $event->freelancer->name . " Rejected Your Job Invite",
                    'message'      => $event->freelancer->first_name . " rejected your invitation to your job. Try inviting more freelancers.",
                    'owner_type'   => 'user'
                ] );

                break;

        }

        /* Send invite response email */
        $event->client->notify( new \App\Notifications\UserRespondedToJobInvite( $event->client, $event->freelancer, $event->job, $event->response ) );

        /* Update any related invitiations */
        $invites = \App\Invite::where( "client_id", $event->client->id )->where( "freelancer_id", $event->freelancer->id )->where( "job_id", $event->job->id )->get();
        foreach ($invites as $invite) {
            $invite->status = $inviteStatus;
            $invite->save();
        }
    }
}
