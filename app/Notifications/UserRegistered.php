<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\SageMailMessage;

class UserRegistered extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        \App\MailLog::create( [
            'to_user_id'   => $user->id,
            'type'         => 'welcome',
            'system'       => true
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
        return (new SageMailMessage)
                    ->subject('Welcome to SageGroupy')
                    ->level('success')
                    ->greeting("Welcome " . $this->user->first_name . ",")
                    ->line('SageGroupy is a free to use freelancing platform. To get the best results from SageGroupy, we recommend the following:')
                    ->list( [
                        "Complete your profile details. ",
                        "Add references for past work you have completed, including screenshots & links.",
                        "Search for & join agencies that compliment your skills on the SageGroupy platform.",
                        "Add your relevant skills to your SageGroupy profile and set your rates based on your experience level.",
                        "Create reference jobs and invite previous clients to provide feedback.",
                    ] )
                    ->action('Login to SageGroupy', url('/login'))
                    ->line('Thank you for joining SageGroupy!');
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
