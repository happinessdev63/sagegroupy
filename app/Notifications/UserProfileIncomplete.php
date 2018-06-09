<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserProfileIncomplete extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user )
    {
        $this->user = $user;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $user->id,
            'type'         => 'profile-incomplete',
            'system'       => true
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
            ->subject( $this->user->first_name . ', Please Complete Your Profile' )
            ->level( 'success' )
            ->greeting( $this->user->first_name . ', Please Complete Your Profile' )
            ->line( 'We noticed that your profile is incomplete. Please take a few minutes to complete your profile on SageGroupy.' )
            ->line( 'Freelancers with complete profiles are 80% more likely to get invited to a job.' )
            ->action( 'Edit Your Profile', url( '/profile/edit/' . $this->user->id ) );
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
