<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserReceivedJobMessage extends Notification
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
    public function __construct( \App\User $user, \App\User $sender, \App\Job $job, $message )
    {
        $this->user = $user;
        $this->job = $job;
        $this->sender = $sender;
        $this->message = $message;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'job_id'       => $job->id,
            'type'         => 'job-message'
        ] );
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return ( new SageMailMessage )
            ->subject( 'You have a new message about the job  ' . $this->job->title )
            ->level( 'success' )
            ->greeting( "New message regarding the job " . $this->job->title . "," )
            ->line( str_limit( $this->message, 400 ) )
            ->action( 'View Job', url( '/job/' . $this->job->id ) );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => $this->user
        ];
    }
}
