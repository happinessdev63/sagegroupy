<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AgencyJobEnded extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $job;
    public $message;
    public $agency;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, \App\User $sender, \App\Job $job, \App\Agency $agency, $message = "")
    {
        $this->user = $user;
        $this->job = $job;
        $this->sender = $sender;
        $this->message = $message;
        $this->agency = $agency;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'job_id'       => $job->id,
            'type'         => 'job-ended-agency'
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
            ->subject( 'A client has ended your job ' . $this->job->title )
            ->level( 'success' )
            ->greeting( "A client has ended your job  " . $this->job->title  )
            ->line( "Thank you for using SageGroupy." )
            ->line( "Your job with agency \"". $this->agency->name . "\" has now been marked as completed / closed by the client. Keep up the good work!" )
            ->line( "You can view the clients feedback and rating on the job details page." )
            ->line( "Reason for ending job: " )
            ->line( $this->job->end_reason ?: 'None provided.' )
            ->action( 'View Job Details', url( '/job/' . $this->job->id ) );
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
