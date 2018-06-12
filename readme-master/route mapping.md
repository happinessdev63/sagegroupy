# Route mapping

All laravel router are defined in your route files, which are located in the `routes` directory.

The routes/web.php file defines routes that are for your web interface. These routes are assigned the `web` middleware group, which
provides features like session state and CSRF protection. The routes in `routes/api.php` are stateless and are assigned the `api` middleware group.

The routes defined in  `routes/web.php` may be accessed by entering the defined route's URL in your browser.

Routes defined in the `routes/api.php` file are nested within a route group by the RouteServiceProvider.
Within this group, the `/api` URL prefix is automatically applied so you do not need to manually apply it to every route in the file.

** _middleware_ **
>Middleware provide a convenient mechanism for filtering HTTP request entering application.
>There are several middleware included in the Laravel framework, including middleware for authentification and CSRF protection. All of these middleware are located in the app/Http/Middleware.
>If we would like to assign middleware to specific routes, you should first assign the middleware a key in our app/Http.kernel.php.
```
  'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
  'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
  'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
  'can' => \Illuminate\Auth\Middleware\Authorize::class,
  'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
  'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
  'adminOnly' => \App\Http\Middleware\RedirectIfNotAdmin::class,
```
>Also we can group several middleware under a single key to make them easier to assign to routes using `$middlewareGroups` property of our Http kernel.
```
\App\Http\Middleware\EncryptCookies::class,
\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
...
\GeneaLabs\LaravelCaffeine\Http\Middleware\LaravelCaffeineDripMiddleware::class,
```
> By using middlewareGroups, It is more convenient to assign many middleware to a route a once.
```
Route::get( '/admin/users', 'Admin\UsersController@index' )->middleware( [ 'auth', 'adminOnly' ] );
```




## web.php

In this site application, We have divided routes as `lander routes`, `Admin Routes`, `User & Profile routes`, `References`, `Job routes` and `agencies` according to pages.

- Lander routes

  This routes consisted of common routes such as `/share/{email}`, `/previewEmail/{email}` and so on.

        Route::get( '/', function () {
            return view("home");
        } );

        ...
        Route::get( '/share/{email}', 'HomeController@share' );
        Route::get( '/previewEmail/{email}', 'HomeController@previewEmail' );
- Admin Routes

  This routes base on route according to Admin action.

        Route::get( '/admin/users', 'Admin\UsersController@index' )->middleware( [ 'auth', 'adminOnly' ] );
        Route::get( '/admin/users/loginAs/{user}', 'Admin\UsersController@loginAsUser' )->middleware( [ 'auth', 'adminOnly' ] );

        ...
        Route::get( '/admin/settings', 'Admin\SettingsController@index' )->middleware( [ 'auth','adminOnly' ] );
        Route::get( '/admin/mailLogs', 'Admin\SettingsController@mailLogs' )->middleware( [ 'auth','adminOnly' ] );
- User & Profile routes.

  This routes have pages accoring to user's profile.

        Route::get('/dashboard', 'ProfileController@dashboard')->middleware( [ 'auth' ] );
        Route::get( '/profileWizard', 'ProfileController@profileWizard' )->middleware( [ 'auth' ] );

        ...
        Route::get('/profile/edit/{user}', 'ProfileController@profileEdit')->middleware( [ 'auth' ] );
        Route::get( '/search', 'ProfileController@search' )->middleware( [ 'auth' ] );

- References

  This routes have page according to general reference about profile.

      Route::get( '/generalReference/{generalReference}', 'ProfileController@viewGeneralReference' );

- Job routes

  This routes consist of the pages according to Job.

      Route::get( '/jobs/create', 'JobsController@create' )->middleware(['auth']);
      Route::get( '/jobs/createReference', 'JobsController@createReference' )->middleware(['auth']);
      ...
      Route::get( '/userJobs', 'JobsController@userJobs' )->middleware(['auth']);

- Agency routes

  This routes have the several pages according to Agency.

      Route::get( '/agencies/create', 'AgenciesController@create' )->middleware( [ 'auth' ] );
      Route::get( '/agencies', 'AgenciesController@find' )->middleware( [ 'auth' ] );

      ...
      Route::get( '/agency/{agency}', 'AgenciesController@index' )->middleware( [ 'auth' ] );

## app.php

All routes within this group will be prepended with `/apiv1/`.
Api routes was including `Auth, Listings API, Profiles, Jobs, References, Links, Settings, Skills, Users, Events, Cities, Notifications, Agencies, Files` route category  according each functions.

| category  | routes |
|:---------|:---------------|
| Auth | <ul><li>Route::post('login', 'Api\AuthController@login');</li><li>Route::post('register', 'Auth\RegisterController@register');</li></ul> |
| Listings  | <ul><li>Route::get("users", 'Api\UsersController@index');</li><li>Route::get("freelancers", 'Api\UsersController@freelancerSearch');</li><li>...</li><li>Route::get("mailLogs", 'Api\MailLogsController@index');</li></ul> |
| Profiles  | <ul><li>Route::post( '/profile/update/{user}', 'Api\UsersController@update' );</li><li>Route::post( '/profile/update/{user}/role', 'Api\UsersController@updateRole' );</li><li>...</li><li>Route::get( '/profile/{user}', 'Api\UsersController@fetch' );</li></ul> |
| Jobs  | <ul><li>Route::get( '/jobs/{job}', 'Api\JobsController@fetch' );</li><li>Route::post( '/jobs/update/{job}', 'Api\JobsController@update' );</li><li>...</li><li>Route::post( '/jobs/invite/{job}', 'Api\JobsController@inviteUser' );</li></ul> |
| References  | <ul><li>Route::post( '/references/update/{generalReference}', 'Api\GeneralReferencesController@update' );</li><li>Route::post( '/generalReference/{generalReference}/clientFeedback', 'Api\GeneralReferencesController@clientFeedback' );</li><li>...</li><li>Route::post( '/references/delete/{generalReference}', 'Api\GeneralReferencesController@delete' );</li></ul> |
| Links  | <ul><li>Route::get( '/links/{link}', 'Api\LinksController@fetch' );</li><li>Route::post( '/links/update/{link}', 'Api\LinksController@update' );</li><li>...</li><li>Route::post( '/links/delete/{link}', 'Api\LinksController@delete' );</li></ul> |
| Settings  | <ul><li>Route::post( '/settings/update/{type}', 'Admin\SettingsController@update' );</li></ul> |
| Skills  | <ul><li>Route::post( '/skills/addUserSkill', 'Api\SkillsController@addSkill' );</li><li>Route::post( '/skills/removeUserSkill/{skill}', 'Api\SkillsController@removeSkill' );</li><li>Route::post( '/skills/delete/{skill}', 'Api\SkillsController@delete' );</li></ul> |
| Users  | <ul><li>Route::get( '/users/{user}', 'Api\UsersController@fetch' );</li></ul> |
| Events  | <ul><li>Route::post("/events/view", 'Api\EventsController@recordView');</li></ul> |
| Cities  | <ul><li>Route::get("/cities", 'Api\CitiesController@fetchByCountryCode');</li></ul> |
| Notifications  | <ul><li>Route::get( "/notifications", 'Api\NotificationsController@index' );</li><li>Route::post( "/feedback", 'Api\NotificationsController@userFeedback' );</li><li>...</li><li>Route::get( '/notifications/{notification}', 'Api\NotificationsController@fetch' );</li></ul> |
| Agencies  | <ul><li>Route::get( '/agencies/{agency}', 'Api\AgenciesController@fetch' );</li><li>Route::post( '/agencies/update/{agency}', 'Api\AgenciesController@update' );</li><li>...</li><li>Route::post( '/agencies/delete/{agency}', 'Api\AgenciesController@delete' );</li></ul> |
| Files  | <ul><li>Route::post( '/files/create/{folder}', 'Api\FilesController@create' );</li><li>Route::post( '/files/create', 'Api\FilesController@create' );</li><li>...</li><li>Route::post( '/files/agencyAvatar/{agency}', 'Api\FilesController@agencyAvatar' );</li></ul> |
