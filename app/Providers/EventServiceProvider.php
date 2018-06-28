<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
      
        /* Message & Notification Events */
        'App\Events\AgencyInviteEvent' => [
            'App\Listeners\SendAgencyInviteNotification',
        ],
        'App\Events\AgencyInviteRequestEvent' => [
            'App\Listeners\SendAgencyInviteRequestNotification',
        ],
        'App\Events\AgencyInviteResponseEvent' => [
            'App\Listeners\HandleAgencyInviteResponse',
        ],
        'App\Events\JobInviteEvent' => [
            'App\Listeners\SendJobInviteNotification',
        ],
        'App\Events\AgencyJobInviteEvent' => [
            'App\Listeners\SendAgencyJobInviteNotification',
        ],
        'App\Events\JobInviteResponseEvent' => [
            'App\Listeners\HandleJobInviteResponse',
        ],
        'App\Events\JobMessageEvent' => [
            'App\Listeners\SendJobMessageNotification',
        ],
        'App\Events\AgencyMessageEvent' => [
            'App\Listeners\SendAgencyMessageNotification',
        ],
        'App\Events\UserMessageEvent' => [
            'App\Listeners\SendUserMessageNotification',
        ],
        'App\Events\JobEndedEvent' => [
            'App\Listeners\SendJobEndedNotification',
        ],
        'App\Events\JobReferenceRequestEvent' => [
            'App\Listeners\SendJobReferenceRequestNotification',
        ],
        'App\Events\GeneralReferenceRequestEvent' => [
            'App\Listeners\SendGeneralReferenceRequestNotification',
        ],
        /* View events */
        'App\Events\ProfileViewedEvent' => [
            'App\Listeners\ProfileViewedListener',
        ],
        'App\Events\JobViewedEvent' => [
            'App\Listeners\JobViewedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
