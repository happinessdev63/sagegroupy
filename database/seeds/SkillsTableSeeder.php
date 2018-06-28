<?php

use App\Skill;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            'PHP Programming',
            'Wordpress Development',
            'Web Design',
            'SEO - Search Engine Optimization',
            'Social Media Marketing',
            '3D Animation',
            '4D Animation',
            'Copywriting',
            'Writer',
            'UI Designer',
            'UX Specialist',
            'Game Developer',
            'Unity 3D Developer',
            'Mobile Application Developer',
            'Android Developer',
            'iOS Developer',
            'Laravel Framework',
            'Twitter Bootstrap',
            'jQuery',
            'MySQL',
            'Server Administration'
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'name' => $skill,
                'slug' => str_slug($skill),
                'category' => 'default',
                'keywords' => str_replace(" ", ",", $skill)
            ]);
        }

        $users = \App\User::where("role", 'like', '%freelancer%')->get();
        $skills = \App\Skill::get();
        $levels = [ 'Entry', 'Junior', 'Intermediate', 'Senior', 'Expert' ];

        foreach ($users as $user) {
            if (rand(1,100) < 15) {
                continue;
            }

            foreach ($skills as $skill) {
                if (rand(1,100) < 30) {
                    continue;
                }

                if ($user->skills()->count() >= 12) {
                    continue;
                }

                $user->skills()->syncWithoutDetaching( [
                    $skill->id => [
                        'rate'       => rand(5,100),
                        'level'      => $levels[array_rand($levels)],
                        'experience' => rand(0,15)
                    ]
                ] );

            }
        }
    }
}
