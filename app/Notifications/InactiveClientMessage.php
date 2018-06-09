<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InactiveClientMessage extends Notification
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
            'type'         => 'inactive-client',
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
            ->subject( $this->user->first_name . ', We\'ve Missed You on SageGroupy!' )
            ->level( 'success' )
            ->greeting( 'We\'ve Missed You on SageGroupy!' )
            ->line( 'We noticed that you signed up as a client on SageGroupy ' . $this->user->created_at->diffForHumans() . ' but you have not created any jobs yet.' )
            ->line( 'To create a new job, click the button below and fill in your job details. It only takes a minute. After you have created a new job you can invite freelancers and agencies to bid on the job.' )
            ->line( 'If you need help or have any questions, please contact us.')
            ->action( 'Create a New Job', url( '/jobs/create' ) );
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
