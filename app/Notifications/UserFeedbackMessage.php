<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserFeedbackMessage extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $job;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, $message = "" )
    {
        $this->user = $user;

        $this->message = $message;

        \App\MailLog::create( [
            'to_user_id'   => '1',
            'from_user_id' => $user->id,
            'job_id'       => null,
            'type'         => 'user-feedback-sent',
            'system'       => 1
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
            ->subject( 'User Feedback / Support Request From ' . $this->user->name )
            ->level( 'success' )
            ->greeting( $this->user->name . " submitted the following message: " )
            ->line( $this->message )
            ->action( 'View User', url( '/profile/' . $this->user->id ) );
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
