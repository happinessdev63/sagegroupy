<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
use Illuminate\Support\Facades\Auth;

/* Auth routes */
Auth::routes();
Route::get( 'logout', function () {
    Auth::logout();
    return redirect( '/login' );
} );

/* Lander Routes */
Route::get( '/', function () {
    return view("home");
} );

Route::get( '/faq', function () {
    return view( "lander.faq" );
} );

Route::get( '/resources', function () {
    return view( "lander.resources" );
} );

Route::get( '/about', function () {
    return view( "lander.about" );
} );

Route::get( '/contact', function () {
    return view( "lander.contact" );
} );

Route::post( '/contact', function () {
    return ['success' => true, 'message' => "Your message has been sent!"];
} );

Route::get( '/terms', function () {
    return view( "lander.terms" );
} );

Route::get( '/privacy', function () {
    return view( "lander.privacy" );
} );

Route::post( '/prelaunch', 'HomeController@prelaunchSignup' );
Route::post( '/prelaunchSurvey', 'HomeController@prelaunchSurvey' );
Route::get( '/prelaunch', 'HomeController@prelaunch' );
Route::get( '/share/{email}', 'HomeController@share' );
Route::get( '/previewEmail/{email}', 'HomeController@previewEmail' );

/* Admin Routes */
Route::get( '/admin/users', 'Admin\UsersController@index' )->middleware( [ 'auth', 'adminOnly' ] );
Route::get( '/admin/users/loginAs/{user}', 'Admin\UsersController@loginAsUser' )->middleware( [ 'auth', 'adminOnly' ] );
Route::get( '/admin/jobs', 'Admin\JobsController@index' )->middleware( [ 'auth', 'adminOnly' ] );
Route::get( '/admin/agencies', 'Admin\AgenciesController@index' )->middleware( [ 'auth', 'adminOnly' ] );
Route::get( '/admin/skills', 'Admin\SkillsController@index' )->middleware( [ 'auth', 'adminOnly' ] );
Route::get( '/admin/settings', 'Admin\SettingsController@index' )->middleware( [ 'auth','adminOnly' ] );
Route::get( '/admin/mailLogs', 'Admin\SettingsController@mailLogs' )->middleware( [ 'auth','adminOnly' ] );

/* User & Profile routes */
// Route::get('/dashboard', 'ProfileController@dashboard')->middleware( [ 'auth' ] );
Route::get('/dashboard', function(){
  dd(Auth()->user()); die();
});
Route::get( '/profileWizard', 'ProfileController@profileWizard' )->middleware( [ 'auth' ] );
Route::get('/profile', 'ProfileController@profile');
Route::get('/profile/{user}', 'ProfileController@viewProfile');
Route::get('/p/{shareId}', 'ProfileController@viewProfileShareId');
Route::get('/profile/edit/{user}', 'ProfileController@profileEdit')->middleware( [ 'auth' ] );
Route::get( '/search', 'ProfileController@search' )->middleware( [ 'auth' ] );

/* References */
Route::get( '/generalReference/{generalReference}', 'ProfileController@viewGeneralReference' );

/* Job routes */
Route::get( '/jobs/create', 'JobsController@create' )->middleware(['auth']);
Route::get( '/jobs/createReference', 'JobsController@createReference' )->middleware(['auth']);
Route::get( '/job/{job}', 'JobsController@index' );
Route::get( '/jobs/editReference/{job}', 'JobsController@editReference' )->middleware( [ 'auth' ] );
Route::get( '/jobs/edit/{job}', 'JobsController@edit' )->middleware(['auth']);
Route::get( '/userJobs', 'JobsController@userJobs' )->middleware(['auth']);

/* Agency routes */
Route::get( '/agencies/create', 'AgenciesController@create' )->middleware( [ 'auth' ] );
Route::get( '/agencies', 'AgenciesController@find' )->middleware( [ 'auth' ] );
Route::get( '/userAgencies', 'AgenciesController@userAgencies' )->middleware( [ 'auth' ] );
Route::get( '/agencies/edit/{agency}', 'AgenciesController@edit' )->middleware( [ 'auth' ] );
Route::get( '/agencies/createReference/{agency}', 'JobsController@createAgencyReference' )->middleware( [ 'auth' ] );
Route::get( '/agency/{agency}', 'AgenciesController@index' )->middleware( [ 'auth' ] );
