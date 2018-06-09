<?php

use Illuminate\Database\Seeder;

use App\Services\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = app( '\App\Services\Settings' );

        $settings->save( [
            [
                'name'        => 'high_rate_threshold',
                'value'       => '1.3',
                'description' => "How far above the average rates before warning/suggestion to a user to lower their rate."
            ],
            [
                'name'        => 'low_rate_threshold',
                'value'       => '0.7',
                'description' => "How far below the average rates before warning/suggestion to a user to raise their rate."
            ],
            [
                'name'        => 'low_user_count_threshold',
                'value'       => '4',
                'description' => "How many users can have the same skill before we stop suggesting the user to raise their rates because of low competition."
            ],
            [
                'name'        => 'max_skills',
                'value'       => '12',
                'description' => "Maximum number of skills a freelancer can add to their profile."
            ],
            [
                'name'        => 'suggestion_high_rate',
                'value'       => 'Your rate is [PERCENTAGE]% higher than the average rate for this skill level. You may want to lower it.',
                'description' => "Suggestion for high rates."
            ],
            [
                'name'        => 'suggestion_low_rate',
                'value'       => 'Your rate is [PERCENTAGE]% lower than the average rate for this skill level. You may want to raise it.',
                'description' => "Suggestion for low rates."
            ],
            [
                'name'        => 'suggestion_no_users',
                'value'       => 'No other freelancers have selected this skill yet. You may be able to raise your rates.',
                'description' => "Suggestion for no users."
            ],
            [
                'name'        => 'suggestion_low_user_count',
                'value'       => 'Only [NUM_USERS]  freelancer(s) have selected this skill. You may be able to raise your rates. ',
                'description' => "Suggestion for low user count."
            ],

        ] );
    }
}
