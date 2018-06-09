<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrelaunchAdminSurveyNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Array
     */
    public $responses;

    public $referrer;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $responses, $email = '', $referrer = '' )
    {
        $this->responses = $responses;
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
        $this->subject( "Notification: New Prelaunch Survey Response" );
        return $this->view( 'emails.prelaunchAdminSurveyNotificationInlined' );
    }
}
