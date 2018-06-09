<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define( App\User::class, function ( Faker\Generator $faker ) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt( 'secret' ),
        'remember_token' => str_random( 10 ),
        'role'           => $faker->randomElement( [ 'client', 'freelancer', 'client_freelancer' ] ),
        'city'           => $faker->city,
        'country'        => $faker->country,
        'bio'            => $faker->paragraphs( rand( 1, 3 ), true )
    ];
} );

$factory->define( App\Job::class, function ( Faker\Generator $faker ) {
    return [
        'title'         => $faker->sentence(),
        'job_id'        => str_random( 5 ),
        'description'   => $faker->paragraphs( rand( 2, 6 ), true ),
        'payment_terms' => $faker->randomElement( [ 'milestone', 'hourly_weekly', 'salary', 'tbd' ] ),
        'type'          => 'standard',
        'completed'     => $faker->boolean( 30 ),
        'status'        => 'new',
        'end_reason'    => '',
        'public'        => true,
        'public_files'  => true,
        'invite_client' => $faker->boolean( 75 ),
    ];

} );
