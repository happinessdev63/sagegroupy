<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendInactiveClientReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:inactiveclients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for clients who have not created any jobs.';

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
        \App\User::where( "role", 'LIKE', '%client%' )->whereDoesntHave('clientJobs')->chunk( 100, function ( $users ) {
            foreach ($users as $user) {
                $this->sendReminder( $user );
            }
        } );
    }

    private function sendReminder( \App\User $user )
    {

        $this->info( "Sending reminder for {$user->name} with {$user->clientJobs->count()} jobs." );

        $user->notify( new \App\Notifications\InactiveClientMessage( $user ) );

    }
}
