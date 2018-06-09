<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateProfileScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update profile search scores.';

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

        \App\User::where("id",">",0)->chunk( 100, function ( $users ) {
            foreach ($users as $user) {
                $this->updateScore($user);
            }
        } );
    }

    /**
     * @todo - Use \App\User method to update score
     * @param \App\User $user
     */
    private function updateScore(\App\User $user) {

        $score = 0;
        $completeScore = 0;

        if ($user->getAvatarAttribute() != '/img/avatar.jpg') {
            $score++;
            $completeScore++;
        }

        if (strlen($user->bio) >= 100) {
            $score++;
            $completeScore++;
        }

        if ($user->tagline != 'No tagline set.') {
            $score++;
            $completeScore++;
        }

        if (strlen($user->company) > 0) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->company_bio ) > 0 ) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->phone ) > 0 ) {
            $score++;
            $completeScore++;
        }

        if ( $user->skills()->count() > 0) {
            $score += $user->skills()->count();
            $completeScore++;
        }

        if ( $user->freelancerJobs()->count() > 0 ) {
            $score += $user->freelancerJobs()->count();
        }

        if ( $user->agencies()->count() > 0 ) {
            $score += $user->agencies()->count();
        }

        $user->update(
            [
                'search_score' => $score,
                'complete_score' => $completeScore
            ]
        );

    }
}
