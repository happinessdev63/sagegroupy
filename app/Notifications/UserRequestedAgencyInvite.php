<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRequestedAgencyInvite extends Notification
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
            'type'         => 'agency-invite-request'
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
            ->subject( 'A user has requested to join your agency ' . $this->agency->name )
            ->level( 'success' )
            ->greeting( $this->sender->name . " Would Like to Join Your Agency" )
            ->line( "If you would like this user to join your agency, please send them an invite. Otherwise, you can ignore this email." )
            ->action( 'Manage Your Agency', url( '/agencies/edit/' . $this->agency->id ) )
            ->action( 'View Users Profile', url( '/profile/' . $this->sender->id ) );
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
