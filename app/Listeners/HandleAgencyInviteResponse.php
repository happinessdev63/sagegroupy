<?php

namespace App\Listeners;

use App\Agency;
use App\Events\AgencyInviteResponseEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class HandleAgencyInviteResponse
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
     * @todo - Send agency invite emails
     */
    public function handle(AgencyInviteResponseEvent $event)
    {

        $event->agency->load("owner");
        switch ($event->response) {
            case "accept":

                $agencyNotification = Notification::create( [
                    'type'           => "agency-invite",
                    'from_user_id'   => $event->user->id,
                    'to_user_id'     => $event->user->id,
                    'agency_id'      => $event->agency->id,
                    'from_agency_id' => $event->agency->id,
                    'owner_id'       => $event->agency->id,
                    'status'         => 'unread',
                    'title'          => $event->user->name . " Accepted Your Invite",
                    'message'        => $event->user->first_name . " is now a member of your agency.",
                    'owner_type'     => 'agency'
                ] );

                $event->agency->freelancers()->syncWithoutDetaching([$event->user->id]);

                /* Send invite response email */
                $event->agency->owner->notify( new \App\Notifications\UserRespondedToAgencyInvite( $event->agency->owner, $event->user, $event->agency, $event->response ) );

                break;

            case "reject":

                $agencyNotification = Notification::create( [
                    'type'           => "agency-invite",
                    'from_user_id'   => $event->user->id,
                    'to_user_id'   => $event->user->id,
                    'agency_id'      => $event->agency->id,
                    'from_agency_id' => $event->agency->id,
                    'owner_id'       => $event->agency->id,
                    'status'         => 'unread',
                    'title'          => $event->user->name . " Rejected Your Invite",
                    'message'        => $event->user->first_name . " rejected your invitation to join this agency. You can resend the invite in 24 hours.",
                    'owner_type' => 'agency'
                ] );

                /* Send invite response email */
                $event->agency->owner->notify( new \App\Notifications\UserRespondedToAgencyInvite( $event->agency->owner, $event->user, $event->agency, $event->response ) );

                break;

            case "revoke":

                Notification::where("from_agency_id", $event->agency->id)
                    ->where("to_user_id", $event->user->id)
                    ->where("type","agency-invite")
                    ->delete();

                break;


        }
    }
}
