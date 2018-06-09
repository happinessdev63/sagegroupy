<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendIncompleteProfileReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for freelancers who have incomplete profiles.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\User::where("role",'LIKE', '%freelancer%')->where('complete_score','<',5)->chunk( 100, function ( $users ) {
            foreach ($users as $user) {
                $this->sendReminder( $user );
            }
        } );
    }

    private function sendReminder(\App\User $user) {

        $this->info("Sending reminder for {$user->name} with profile score of {$user->complete_score}.");

        $user->notify( new \App\Notifications\UserProfileIncomplete( $user ) );

    }
}
