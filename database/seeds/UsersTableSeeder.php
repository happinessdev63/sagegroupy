<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Add some default user accounts for testing.
     *
     * @return void
     */
    public function run()
    {
        User::create( [
            'name'           => "Admin",
            'email'          => "admin@sagegroupy.com",
            'password'       => bcrypt( 'xhmn7081pkLuJ!' ),
            'remember_token' => str_random( 10 ),
            'role'           => 'admin',
            'bio'            => '<p>Sagegroupy Moderator</p>'
        ] );

        User::create( [
            'name'           => "Ryan Bombard",
            'email'          => "rsbombard@gmail.com",
            'password'       => bcrypt( 'admin123' ),
            'remember_token' => str_random( 10 ),
            'role'           => 'admin',
            'bio'            => 'This is awesome.'
        ] );



        /* Add freelancers and clients
        factory( App\User::class, 25 )->create()->each( function ( $user ) {
            if ($user->isClient) {
                //$user->clientJobs()->save( factory(\App\Job::class )->make() );
                //$user->clientJobs()->save( factory(\App\Job::class )->make() );
            }
        } );
        */

    }
}
