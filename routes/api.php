<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/* All routes within this group will be prepended with /apiv1/ */
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Auth\RegisterController@register');
// Route::post('register', function() {
//   dd("OK");die();
// });

/* Listings API */
Route::get("users", 'Api\UsersController@index');
Route::get("freelancers", 'Api\UsersController@freelancerSearch');
Route::get("jobs", 'Api\JobsController@index');
Route::get( "references", 'Api\GeneralReferencesController@index' );
Route::get("agencies", 'Api\AgenciesController@index');
Route::get("skills", 'Api\SkillsController@index');
Route::get("mailLogs", 'Api\MailLogsController@index');

/* Profiles */
Route::post( '/profile/update/{user}', 'Api\UsersController@update' );
Route::post( '/profile/update/{user}/role', 'Api\UsersController@updateRole' );
Route::post( '/profile/update/{user}/meta', 'Api\UsersController@updateMeta' );
Route::post( '/profile/delete/{user}', 'Api\UsersController@delete' );
Route::post( '/profile/addRec/', 'Api\UsersController@addRecommendation' );
Route::get( '/profile/rec/delete/{rec}', 'Api\UsersController@deleteRecommendation' );
Route::get( '/profile/{user}', 'Api\UsersController@fetch' );
Route::get( '/profile/postPdf/{userId}', 'Api\UsersController@downloadToPdfFile');

/* Jobs */
Route::get( '/jobs/{job}', 'Api\JobsController@fetch' );
Route::post( '/jobs/update/{job}', 'Api\JobsController@update' );
Route::post( '/jobs/end/{job}', 'Api\JobsController@end' );
Route::post( '/jobs/clientFeedback/{job}', 'Api\JobsController@clientFeedback' );
Route::post( '/jobs/create', 'Api\JobsController@create' );
Route::post( '/jobs/createReference', 'Api\JobsController@createReference' );
Route::post( '/jobs/delete/{job}', 'Api\JobsController@delete' );
Route::post( '/jobs/invite/{job}', 'Api\JobsController@inviteUser' );

/* References */
Route::post( '/references/update/{generalReference}', 'Api\GeneralReferencesController@update' );
Route::post( '/generalReference/{generalReference}/clientFeedback', 'Api\GeneralReferencesController@clientFeedback' );
Route::post( '/references/create', 'Api\GeneralReferencesController@create' );
Route::post( '/references/delete/{generalReference}', 'Api\GeneralReferencesController@delete' );

/* Links */
Route::get( '/links/{link}', 'Api\LinksController@fetch' );
Route::post( '/links/update/{link}', 'Api\LinksController@update' );
Route::post( '/links/create', 'Api\LinksController@create' );
Route::post( '/links/delete/{link}', 'Api\LinksController@delete' );

/* Settings */
Route::post( '/settings/update/{type}', 'Admin\SettingsController@update' );

/* Skills */
Route::post( '/skills/addUserSkill', 'Api\SkillsController@addSkill' );
Route::post( '/skills/removeUserSkill/{skill}', 'Api\SkillsController@removeSkill' );
Route::post( '/skills/delete/{skill}', 'Api\SkillsController@delete' );

/* Users */
Route::get( '/users/{user}', 'Api\UsersController@fetch' );

/* Events */
Route::post("/events/view", 'Api\EventsController@recordView');

/* Cities */
Route::get("/cities", 'Api\CitiesController@fetchByCountryCode');

/* Notifications */
Route::get( "/notifications", 'Api\NotificationsController@index' );
Route::post( "/feedback", 'Api\NotificationsController@userFeedback' );
Route::post( '/notifications/update/{notification}', 'Api\NotificationsController@update' );
Route::post( '/notifications/status/{notification}/{status}', 'Api\NotificationsController@updateStatus' );
Route::post( '/notifications/create', 'Api\NotificationsController@create' );
Route::post( '/notifications/sendMessage', 'Api\NotificationsController@sendMessage' );
Route::post( '/notifications/sendBulkMessages', 'Api\NotificationsController@sendBulkMessages' );
Route::post( '/notifications/markAllRead', 'Api\NotificationsController@markAllRead' );
Route::post( '/notifications/delete/{notification}', 'Api\NotificationsController@delete' );
Route::post( '/notifications/revokeInvite/{notification}', 'Api\NotificationsController@revokeInvite' );
Route::post( '/notifications/rejectInvite/{notification}', 'Api\NotificationsController@rejectInvite' );
Route::post( '/notifications/acceptInvite/{notification}', 'Api\NotificationsController@acceptInvite' );
Route::post( '/notifications/rejectJobInvite/{notification}', 'Api\NotificationsController@rejectJobInvite' );
Route::post( '/notifications/acceptJobInvite/{notification}', 'Api\NotificationsController@acceptJobInvite' );
Route::post( '/notifications/requestInvite/{notification}', 'Api\NotificationsController@requestInvite' );
Route::post( '/notifications/inviteFriends', 'Api\NotificationsController@inviteFriends' );
Route::get( '/notifications/{notification}', 'Api\NotificationsController@fetch' );


/* Agencies */
Route::get( '/agencies/{agency}', 'Api\AgenciesController@fetch' );
Route::post( '/agencies/update/{agency}', 'Api\AgenciesController@update' );
Route::post( '/agencies/updateOwner/{agency}', 'Api\AgenciesController@updateOwner' );
Route::post( '/agencies/deleteUser/{agency}', 'Api\AgenciesController@deleteUser' );
Route::post( '/agencies/invite/{agency}', 'Api\AgenciesController@inviteUser' );
Route::post( '/agencies/join/{agency}', 'Api\AgenciesController@joinAgency' );
Route::post( '/agencies/create', 'Api\AgenciesController@create' );
Route::post( '/agencies/createReference', 'Api\AgenciesController@createReference' );
Route::post( '/agencies/delete/{agency}', 'Api\AgenciesController@delete' );


/* Files */
Route::post( '/files/create/{folder}', 'Api\FilesController@create' );
Route::post( '/files/create', 'Api\FilesController@create' );
Route::post( '/files/avatar', 'Api\FilesController@avatar' );
Route::post( '/files/agencyAvatar/{agency}', 'Api\FilesController@agencyAvatar' );

/* Token Based API
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
*/
