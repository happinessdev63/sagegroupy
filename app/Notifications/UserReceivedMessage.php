<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserReceivedMessage extends Notification
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
    public function __construct( \App\User $user, \App\User $sender, $message )
    {
        $this->user = $user;
        $this->sender = $sender;
        $this->message = $message;

        \App\MailLog::create([
            'to_user_id' => $user->id,
            'from_user_id' => $sender->id,
            'type' => 'message'
        ]);
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
            ->subject( 'New Message From ' . $this->sender->name )
            ->level( 'success' )
            ->greeting( "You Received a New Message From " . $this->sender->first_name . "," )
            ->line( str_limit($this->message, 400)  )
            ->action( 'Reply', url( '/dashboard' ) );
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
