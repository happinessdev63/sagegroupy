<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRespondedToAgencyInvite extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $agency;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, \App\User $sender, \App\Agency $agency, $status )
    {
        $this->user = $user;
        $this->agency = $agency;
        $this->sender = $sender;
        $this->status = $status;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'type'         => 'agency-invite-response'
        ] );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via( $notifiable )
    {
        return [ 'mail' ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail( $notifiable )
    {

        if ( $this->status == 'accept' ) {
            return ( new SageMailMessage )
                ->subject( $this->sender->name . " Accepted Your Agency Invitation" )
                ->level( 'success' )
                ->greeting( $this->sender->name . " Accepted Your Agency Invitation" )
                ->line( $this->sender->first_name . " has now been added as an active freelancer to your agency, " . $this->agency->name . "." )
                ->action( 'View Agency', url( '/agency/' . $this->agency->id ) );
        }

        if ( $this->status == 'reject' ) {
            return ( new SageMailMessage )
                ->subject( $this->sender->name . " Rejected Your Agency Invitation" )
                ->level( 'success' )
                ->greeting( $this->sender->name . " Rejected Your Agency Invitation" )
                ->line( "Don't worry, there's plenty of freelaners to choose from. Make sure you select freelancers that have the appropriate (complimentary) skills for your agency." )
                ->action( 'Search For Freelancers', url( '/search' ) );
        }

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray( $notifiable )
    {
        return [
            'user' => $this->user
        ];
    }
}
