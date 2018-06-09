<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendJobReferenceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:jobreference';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for clients to provide job references';

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
        \App\Job::where("completed", true)->whereDoesntHave('references')->chunk( 100, function ( $jobs ) {
            foreach ($jobs as $jobs) {
                $this->sendReminder( $jobs );
            }
        } );
    }

    private function sendReminder(\App\Job $job) {

        if (!$job->hasClient() || !$job->hasFreelancer()) {
            return;
        }

        $this->info("Sending reminder for {$job->title}");

        $clientNotification = \App\Notification::create( [
            'type'         => "job-reminder",
            'to_user_id'   => $job->client->id,
            'from_user_id' => $job->freelancer->id,
            'job_id'       => $job->id,
            'owner_id'     => $job->client->id,
            'title'        => "Please provide feedback for job, '" .$job->title . "'",
            'message'      => "This is a friendly reminder that you have not yet provided feedback for the freelancer associated with this job. You will continue to be be reminded until you provide feedback for this job.",
            'status'       => 'unread',
            'owner_type'   => 'user'
        ] );

        $job->client->notify( new \App\Notifications\JobFeedbackRequired( $job->client, $job->freelancer, $job ) );

    }
}
