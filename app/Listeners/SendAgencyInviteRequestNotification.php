<?php

namespace App\Listeners;

use App\Events\AgencyInviteRequestEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;
class SendAgencyInviteRequestNotification
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
     * @param  AgencyInviteRequestEvent  $event
     * @return void
     * @todo - Send invite request email
     */
    public function handle(AgencyInviteRequestEvent $event)
    {

        $event->agency->load('owner');

        $agencyNotification = Notification::create( [
            'type'           => "agency-invite-request",
            'from_user_id'   => $event->user->id,
            'agency_id'      => $event->agency->id,
            'from_agency_id' => $event->agency->id,
            'owner_id'       => $event->agency->id,
            'status'         => 'unread',
            'title'          => $event->user->name . " Has Requested to Join Your Agency",
            'message'        => "If you would like this user to join your agency, please send them an invite. Otherwise you can ignore this request.",
            'owner_type'     => 'agency'
        ] );

        $userNotification = Notification::create( [
            'type'           => "agency-invite-request",
            'to_user_id'     => $event->user->id,
            'agency_id'      => $event->agency->id,
            'from_agency_id' => $event->agency->id,
            'owner_id'       => $event->user->id,
            'status'         => 'unread',
            'title'          => "You Requested to Join \"" . $event->agency->name . "\"",
            'message'        => "If the agency accepts your request you will receive an invite to join the agency.",
            'owner_type'     => 'user'
        ] );

        /* Send email notification to agency owner */
        $event->agency->owner->notify( new \App\Notifications\UserRequestedAgencyInvite( $event->agency->owner, $event->user, $event->agency) );
    }
}
