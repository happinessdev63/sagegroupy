<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UseInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Array
     */
    public $email;
    public $name;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( \App\User $user, $email, $name = '' )
    {
        $this->user = $user;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject( "Welcome to Sagegroupy" );
        return $this->view( 'emails.prelaunchWelcomeInlined' );
    }
}
