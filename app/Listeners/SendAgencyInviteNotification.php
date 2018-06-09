<?php

namespace App\Listeners;

use App\Events\AgencyInviteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class SendAgencyInviteNotification
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
     * @param  AgencyInviteEvent  $event
     * @return void
     * @todo - Send agency invite email notification
     */
    public function handle(AgencyInviteEvent $event)
    {

        $event->agency->load('owner');
        /* Create Two Notifications: 1 for the agency, 1 for the invited user */
        $agencyNotification = Notification::create( [
            'type'           => "agency-invite",
            'to_user_id'     => $event->user->id,
            'agency_id'      => $event->agency->id,
            'from_agency_id' => $event->agency->id,
            'owner_id'       => $event->agency->id,
            'status'         => 'read',
            'title'          => "Invite Sent To " . $event->user->name,
            'message'        => "You invited this user to join your agency. You will be notified when they accept or decline the invitation.",
            'owner_type'     => 'agency'
        ] );

        $userNotification = Notification::create( [
            'type'           => "agency-invite",
            'to_user_id'     => $event->user->id,
            'agency_id'      => $event->agency->id,
            'from_agency_id' => $event->agency->id,
            'owner_id'       => $event->user->id,
            'status'         => 'unread',
            'title'          => "You've Been Invited to Join \"" . $event->agency->name . "\"",
            'message'        => "You were invited to join this agency. Please either accept or decline this invitation as soon as possible.",
            'owner_type'    => 'user'
        ] );

        /* Send the notification email */
        $event->user->notify( new \App\Notifications\UserInvitedToAgency( $event->user, $event->agency->owner, $event->agency ) );
    }
}
