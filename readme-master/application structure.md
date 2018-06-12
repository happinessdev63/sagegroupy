# Application structure

This site application features are divided into three parts: **admin**, **user app** (client, freelancer, client-freelancer) and **auth**(signup, login, forgetpassword, resetpassword).

All of the features are implemented in the following files.

- `app/Http/Controllers/Controller.php` : The application controller
- `routes/web.php` : The routes for the controller methods.
- `resources/*.blade.php` : The Laravel/Blade template used for rendering the content

** _Template_ **

> Laravel/Blade template consists of **HTML tags** and **Vue component(*.vue)** located in the _resources/assets/js/components_ directory.
> Most components using in this site application is **single file Vue component** which defines its JavaScript and HTML template in the same file.
> Single file components provide a very convenient approach to building JavaScript driven applications.
> So when a component is invoked, we can pass parameters to that like following.
```
    <sage-admin-agencies ref="admin-agencies" action = "admin"></sage-admin-agencies>
```
## admin

  The **admin** features are split into five main areas within app -  Agencies, Job, Settings, Skills and Users.

  Each area has a main page and a detail page.

  Folder structure like following.

      sage-master
      ├───   app
          ├─── Console
          ├─── ...
          ├─── Http
            ├─── Controllers
              ├─── Admin
                ├─── * AgenciesController.php
                ├─── * JobsController.php
                ├─── * SettingsController.php
                ├─── * SkillsController.php
                ├─── * UserController.php

  - #### Agencies

    Agencies categories are loaded in the `index()` method of the `AgenciesController` and rendered to the `agencies.blade.php` template of the _resources/admin_ folder.

    `agencies.blade.php` template use `<sage-admin-agencies>` that registered in **app.js** of the _resources/assets/js_ folder.

          Vue.component('sage-admin-agencies', require('./components/admin/Agencies.vue'));

    `<sage-admin-agencies>`component is loaded with `onSort`, `onSelected`, `onFilter`, `onSearch`, `refreshTable` and etc javascript method of the `Agencies.vue` and as various event(sort button is clicked, search button is clicked and so on.), it makes run function following event.

    - `refreshTable()` is a method for table updating by using `AJAX call` after the CRUD operation is applied to the table.

      The parameters are variable of JSON format.

          this.$http.get('/apiv1/agencies', {params : this.options}).then((response) => {
              this.agencies = response.body.data;
              ...
          }, (response) => {
              console.log("Error loading agencies.");
          });

    - `onSearch()` is a method for Agencies searching when search pattern is inputed.

          onSearch(term) {
              this.options.search = term;
              this.refreshTable();
          },
    - ...

  - #### Jobs

    Jobs categories are loaded in the `index()` method of the `JobsController` and rendered to the `jobs.blade.php` template of the _resources/admin_ folder.

    `jobs.blade.php` template use `<sage-admin-jobs>` that registered in **app.js** of the _resources/assets/js_ folder.

          Vue.component('sage-admin-jobs', require('./components/admin/Jobs.vue'));

    `<sage-admin-jobs>`component is loaded with `onSort`, `onSelected`, `onFilter`, `onSearch`, `refreshTable` and etc javascript method of the `Jobs.vue` and as various event(sort button is clicked, search button is clicked and so on.), it makes run function following event.

    - `refreshTable()` is a method for table updating by using `AJAX call` after the CRUD operation is applied to the table.

      The parameters are variable of JSON format.

          this.$http.get('/apiv1/jobs', {params : this.options}).then((response) => {
              this.jobs = response.body.data;
              ...
          }, (response) => {
              console.log("Error loading agencies.");
          });

    - `onSearch()` is a function for Job searching when search pattern is inputed.

          onSearch(term) {
              this.options.search = term;
              this.refreshTable();
          },
    - ...

  - #### Settings

    Settings categories are loaded in the `index()`, `mailLogs()` and `update()` method of the `SettingsController` and rendered to the `settings.blade.php` and `mailLogs.blade.php` template of the _resources/admin_ folder.

    `settings.blade.php` template use `<sage-admin-settings>` that registered in **app.js** of the _resources/assets/js_ folder.

          Vue.component('sage-admin-settings', require('./components/admin/Settings.vue'));

    `<sage-admin-jobs>`component is loaded with `updateEditor`, `saveSettings` and `showNotification` javascript methods of the `Setting.vue` and as various event(save button is clicked and so on.), it makes run function following event.

    - `saveSettings()` is a method for table updating by using `AJAX call` when save button was clicked after the CRUD operation is applied to the table.
            var apiUrl = '/apiv1/settings/update/' + type;
            this.$http.post(apiUrl, this.shared.settings).then((response) => {
                this.$root.showNotification(response.body.message);
                ...
            },
            (response) => {
                ...
            });


  - ...

## Auth

  The **Auth** features are split into four main areas withing app -  ForgotPassword, Login, Register and ResetPassword
  Folder structure like following.

        sage-master
        ├───   app
            ├─── Console
            ├─── ...
            ├─── Http
              ├─── Controllers
                ├─── ...
                ├─── Auth
                  ├─── * ForgotPasswordController.php
                  ├─── * LoginController.php
                  ├─── * RegisterController.php
                  ├─── * ResetPasswordController.php

  - #### ForgotPassword

    As ForgotPassword feature use `use SendsPasswordResetEmails` method of the `ForgotPasswordController`, will send mail to the current user.

  - #### Login

    Login feature is loaded in the `redirect()` method of the `LoginController` and if Login was confirmed, current path change the dashboard or next page (/profile, /agencies ...) as `redirect()`.

  - ...

## User app

  The **User app** features are split into three main areas withing app -  Agencies, Job and Profile.
  It also has only a few of the above areas depending on the user's action(client, freelancer, client-freelancer) using parameters from controller to view.
  Each area has a main page and a detail page.

  Folder structure like following.

        sage-master
        ├───   app
            ├─── Console
            ├─── ...
            ├─── Http
              ├─── Controllers
                ├─── ...
                ├─── * JobsController.php
                ├─── * ProfileController.php
                ├─── * AgenciesController.php


  - #### Jobs

    Jobs categories are loaded in the `create`, `userJobs`, `createReference`, `createAgencyReference`, `editReference` and etc method of the `JobsController` and rendered to the `jobs`,`createReferenceJob`, `index`, `editJob` templates of the _resources_ folder.

    `editJob.blade.php` template use `<sage-create-job>` that registered in **app.js** of the _resources/assets/js_ folder.

          Vue.component('sage-create-job', require('./components/client/CreateJob.vue'));

    `<sage-create-job>`component is loaded with `syncJob`, `fileSuccess`, `confirmDeleteFile`, `deleteFile` and etc javascript method of the `CreateJob.vue` and as various event(sort button is clicked, search button is clicked and so on.), it makes run function following event.

    - `fileSuccess()` is a method for saving file info after file is uploaded successfully.

      The parameters are file.

            var response = JSON.parse(file.xhr.response);
            ...
            this.jobData.files.push({
                name: file.name,
                size: file.size,
                is_image: isImage,
                id: response.id,
                ...
            })

    - `confirmDeleteFile` is a function to open confirm Modal for deleting when delete button was clicked.

          <md-button class="md-icon-button md-list-action" @click="confirmDeleteFile(file)"> --- trigger

          confirmDeleteFile(file) {
              this.selectedFile = file;
              this.$refs['delete-file'].open();
          },
    - ...

  - #### Profile
  Profile categories are loaded in the `dashboard`, `profileWizard`, `viewProfile`, `viewProfileShareId` and etc method of the `ProfileController` and rendered to the `dashboard`, `profile`, `findFreelancer` and etc templates of the _resources_ folder.


  - #### Agencies

    Agencies categories are loaded in the `create`, `userAgencies`, `find`, `index` and `edit` method of the `AgenciesController` and rendered to the `findAgency`, `index`, `editAgency` templates of the _resources/agencies_ folder.

# Digging Deeper

Also our application are using laravel Event.
Laravel's events provides a simple observer implementation, allowing you to subscribe and listen for various events that occur in your application. Event classes are typically stored in the `app/Event` directory, while their listeners are stored in `app/listeners`. For example, If freelancer have just finished the job, we can send a email to freelancer and client. we can raise an `JobEnded` event, which a listener can receive and transform into a email.

** Registering Event & Listeners **

The `EventServiceProvider` in the `app/Providers` include with **sage groupy** application provides a convenient place to register all of application's event listeners.The `listen` property contains an array of all events(keys) and their listener(values). For example, let's add a `JobEnded` event:
```
'App\Events\JobEndedEvent' => [
    'App\Listeners\SendJobEndedNotification',
],
...
```

** Using event **

The other parts such as controller and middleware can use this event like following(`app/Http/controllers/Api/JobsController`):
```
event( new \App\Events\JobEndedEvent( $job, $job->client, $job->agency->owner, $job->agency ) );
```
