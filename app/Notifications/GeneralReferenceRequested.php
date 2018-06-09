<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralReferenceRequested extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $reference;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\GeneralReference $reference, \App\User $user, \App\User $sender, $message )
    {
        $this->reference = $reference;
        $this->user = $user;
        $this->sender = $sender;
        $this->message = $message;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'type'         => 'general-reference-request'
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

        $requestingUser = $this->sender;

        return ( new SageMailMessage )
            ->subject( $requestingUser->name . " has requested a reference on SageGroupy")
            ->level( 'success' )
            ->greeting( "Hello," )
            ->line( $requestingUser->name . " is using the SageGroupy platform to manage their freelancing projects. ")
            ->line( $requestingUser->first_name . " has indicated that you may provide a reference for display on their freelancing profile. It will only take a minute of your time and it will help " . $requestingUser->first_name . " secure future projects. " )
            ->line("Message From Freelancer:")
            ->line( $this->message )
            ->action( 'Provide a Reference', url( '/generalReference/' . $this->reference->id . "?intent=clientReference" ) );
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
