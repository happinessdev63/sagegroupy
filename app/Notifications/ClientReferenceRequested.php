<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientReferenceRequested extends Notification
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
        $this->sender = $sender;
        $this->job = $job;
        $this->message = $message;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'job_id'       => $job->id,
            'type'         => 'client-reference-request'
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

        if ($this->job->hasAgency()) {
            $requestingUser = $this->job->agency->owner;
        } else {
            $requestingUser = $this->job->freelancer;
        }

        return ( new SageMailMessage )
            ->subject( $requestingUser->name . " has requested feedback for a previous job")
            ->level( 'success' )
            ->greeting( "Hello," )
            ->line( $requestingUser->name . " is using the SageGroupy platform to manage their freelancing projects. ")
            ->line( $requestingUser->first_name . " has indicated that they worked for you in the past and would like you to provide a reference for work completed in the past. It will only take a minute of your time and it will help " . $requestingUser->first_name . " secure future projects. " )
            ->line("Related Job: " . $this->job->title)
            ->line("Message From Freelancer:")
            ->line( $this->message )
            ->action( 'Provide a Reference', url( '/job/' . $this->job->id . "?intent=clientReference" ) );
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
