<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrelaunchAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Array
     */
    public $referrer;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $referrer='')
    {
        $this->email = $email;
        $this->referrer = $referrer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Notification: New Signup");
        return $this->view('emails.prelaunchAdminNotificationInlined');
    }
}
