<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserInvited extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, \App\User $sender )
    {
        $this->user = $user;
        $this->sender = $sender;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'type'         => 'user-invite'
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
            ->subject( $this->user->first_name . ' You were invited to join SageGroupy' )
            ->level( 'success' )
            ->greeting( "SageGroupy - The Freelancers Friend" )
            ->line( "One of your friends, " . $this->sender->name . ", has invited you to join the SageGroupy freelancer platform. " )
            ->line( "SageGroupy is a free platform designed specifically for freelancers. Signing up will only take a couple minutes." )
            ->line( " After joining you can create agencies, add previous job references and complete your profile to start attracting new clients. SageGroupy will help boost your freelancing business to the next level quickly & easily. " )
            ->line( "If you are not interested in joining you can simply ignore this email. ")
            ->action( 'Sign Up Now', url( '/register/' ) );
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
