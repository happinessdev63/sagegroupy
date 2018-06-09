<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserInvitedToAgency extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $agency;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, \App\User $sender, \App\Agency $agency )
    {
        $this->user = $user;
        $this->agency = $agency;
        $this->sender = $sender;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'agency_id'    => $agency->id,
            'type'         => 'agency-invite'
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
        return ( new SageMailMessage )
            ->subject( 'You were invited to an agency: ' . $this->agency->name )
            ->level( 'success' )
            ->greeting( "You were invited to an agency: " . $this->agency->name . "" )
            ->line( "You were invited to join this agency. Please either accept or decline this invitation from your dashboard as soon as possible." )
            ->line( "Agencies are a great way to compliment your skills and land larger, higher paying jobs." )
            ->action( 'View Agency', url( '/agency/' . $this->agency->id ) );
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
