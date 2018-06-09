<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AgencyRespondedToJobInvite extends Notification
{
    use Queueable;

    public $user;
    public $sender;
    public $job;
    public $status;
    public $agency;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, \App\User $sender, \App\Agency $agency, \App\Job $job, $status )
    {
        $this->user = $user;
        $this->job = $job;
        $this->agency = $agency;
        $this->sender = $sender;
        $this->status = $status;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'from_user_id' => $sender->id,
            'job_id'       => $job->id,
            'type'         => 'job-invite-response'
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

        if ($this->status == 'accept') {
            return ( new SageMailMessage )
                ->subject( $this->agency->name . " Accepted Your Job Invitation" )
                ->level( 'success' )
                ->greeting( $this->agency->name . " Accepted Your Job Invitation" )
                ->line( $this->agency->name . " has now been assigned as the active agency on your job, " . $this->job->title . "." )
                ->action( 'View Job', url( '/job/' . $this->job->id ) );
        }

        if ($this->status == 'reject') {
            return ( new SageMailMessage )
                ->subject( $this->agency->name. " Rejected Your Job Invitation" )
                ->level( 'success' )
                ->greeting( $this->agency->name . " Rejected Your Job Invitation" )
                ->line( "Don't worry, there's plenty of agencies & freelancers to choose from. Make sure you select freelancers that have the appropriate skills for your job.")
                ->line("Remember, lower rates do not always result in a lower project cost. Some of the best freelancers charge slightly higher rates but are much more productive then the average freelancer. Try searching for freelancers that strike a balance between their skill level and their hourly rates." )

                ->action( 'Search For Freelancers', url( '/search') );
        }

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
