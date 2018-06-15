@extends('layouts.app')

@section('extras')
    @if (Auth::user() && Auth::user()->id)
        <!-- Chat Placeholder -->
        <sage-end-job ref="endJob"></sage-end-job>
    @endif

@endsection

@section('content')

    @if (Auth::user()->is_freelancer)
        <div class="row">

            <div class="col-lg-12 col-sm-12 padding-bottom-10 ">
                <div class="bg-white border-radius-5 margin-bottom-20 md-whiteframe-3dp padding-20" v-cloak>
                    <span class="font-size-20 font-weight-400">{{ $user->name }}</span>
                    <md-progress :md-progress="{{ $user->percent_complete_score }}" class="margin-bottom-5">
                    </md-progress>
                    <span class="text-muted margin-top-5 tour-profile-complete">Your profile is {{ $user->percent_complete_score }}% complete.</span>

                    <div v-if="shared.user.percent_complete_score < 100" class="margin-top-20">
                        <ul class="list-group margin-top-5">
                            @if ($user->avatar == '/img/avatar.jpg')
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Please add a profile picture, pictures are important to build trust with clients.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                            @endif

                            @if (strlen($user->bio) < 100)
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Please update your profile bio and make sure you include details about yourself, your skills & your past experience.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                            @endif

                            @if (strlen($user->phone) < 10)
                                <li class="list-group-item">
                                    <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                    <p class="pull-left">Add a phone number to your profile so we can verify your account.</p>
                                    <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                    <div class="clearfix"></div>
                                </li>
                            @endif

                            @if ($user->tagline == 'No tagline set.')
                                <li class="list-group-item">
                                    <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                    <p class="pull-left">Add a profile tagline to stand out among freelancers. Taglines should be short and catchy.</p>
                                    <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                    <div class="clearfix"></div>
                                </li>
                            @endif

                            @if ($user->skills()->count() < 1)
                                <li class="list-group-item">
                                    <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                    <p class="pull-left">Add one or more relevant skills to your profile. Skills are important so clients can find you when searching for qualified freelancers. </p>
                                    <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                    <div class="clearfix"></div>
                                </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12 tour-jobs" id='jobs'>
                <sage-jobs ref="jobs"></sage-jobs>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center padding-bottom-20 hidden-sm">
                <!-- Dashboard Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="4599851183"
                     data-ad-format="auto"></ins>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12  padding-bottom-20" id='notifications'>
                <sage-notifications  :owner="{{ $user->id }}" v-cloak ref="notifications"></sage-notifications>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12 padding-bottom-20 tour-agencies" id='agencies'>
                <sage-agencies></sage-agencies>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center padding-bottom-20">

                <!-- Dashboard Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="4599851183"
                     data-ad-format="auto"></ins>
            </div>

        </div>
    @endif

    @if ((Auth::user()->is_client && !Auth::user()->is_freelancer) || Auth::user()->isAdmin)
        <div class="row">
            <div class="col-lg-12 col-sm-12 padding-bottom-10 ">
            <div class="bg-white border-radius-5 margin-bottom-20 md-whiteframe-3dp padding-20" v-cloak>
                <span class="font-size-20 font-weight-400">{{ $user->name }}</span>
                <md-progress :md-progress="{{ $user->percent_complete_score }}" class="margin-bottom-5">
                </md-progress>
                <span class="text-muted margin-top-5 tour-profile-complete">Your profile is {{ $user->percent_complete_score }}% complete.</span>

                <div v-if="shared.user.percent_complete_score < 100" class="margin-top-20">
                    <ul class="list-group margin-top-5">
                        @if ($user->avatar == '/img/avatar.jpg')
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Please add a profile picture, pictures are important to build trust with freelancers.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                        @endif

                        @if (strlen($user->bio) < 100)
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Please update your profile bio and make sure you include details about yourself, so freelancers can better understand your motives.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                        @endif

                        @if (strlen($user->phone) < 10)
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Add a phone number to your profile so we can verify your account.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                        @endif

                        @if ($user->tagline == 'No tagline set.')
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">Add a tagline to your profile. Taglines should be short and catchy.</p>
                                <a href="/profile/edit/{{ $user->id }}" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                        @endif

                        @if ($user->clientJobs->count() == 0)
                            <li class="list-group-item">
                                <md-icon class="pull-left text-muted margin-right-10">assignment_late</md-icon>
                                <p class="pull-left">You have not created any jobs yet. Get started by creating a new job and inviting freelancers to bid on your job. </p>
                                <a href="/jobs/create" class="pull-right link-primary">Take Me There</a>
                                <div class="clearfix"></div>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12 tour-jobs padding-bottom-20">
                <sage-jobs ref="jobs"></sage-jobs>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12   padding-bottom-20">
                <sage-notifications id='notifications' :owner="{{ $user->id }}" v-cloak ref="notifications"></sage-notifications>
            </div>
        </div>
    @endif

    @if (Auth::user()->is_client && !Auth::user()->is_freelancer)
        <div class="row">
            <div class="col-lg-12 text-center padding-bottom-20">
                <!-- Dashboard Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="4599851183"
                     data-ad-format="auto"></ins>

            </div>
        </div>
    @endif

    @include('modals.reminderModals')

@endsection

@section('scripts')

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>



    <script>
		window.sageSource.user = <?php echo json_encode( $user ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;

		if (!window.sageSource.user.meta || window.sageSource.user.meta.dashboard_tour_complete != true) {
			$(document).ready(function () {
				//initialize constructor
				window._tour = new ProductTour({
					overlay: true,// optional (true || false) defaults: true
					onStart: function () {
						appBus.$emit('updateUserMeta', [
							{
								field: 'dashboard_tour_complete',
								value: true
							},
							{
								field: 'first_login',
								value: false
							},
						]);
					},
					onChanged: function (e) {
						console.log("Return Value", e);
					},
					onClosed: function (e) {
						console.log("Return Value For On Close", e);
					},
					nextText: '', //optional defaults: 'next'
					prevText: '', //optional defaults: 'prev'
					html: true// optional (true || false) defaults: false
				});
				//can only be called once

				setTimeout(function () {

					var tourSteps = [
						{
							element: '.md-toolbar',//specify the target selector by id or class #search or .header (defaults: body)
							title: 'Welcome to SageGroupy',//title of the tour step
							content: 'Before you get started, let\'s go over some of the core features on your dashboard. You can end this tour at any time by clicking the X.',
							image: '',//specify image to be shown on mobile view
							position: 'bottom'//top, bottom, right, left
						},
						{
							element: '.tour-profile-complete',//specify the target selector by id or class #search or .header (defaults: body)
							title: 'Profile Completion',//title of the tour step
							content: 'Having a complete profile is an important first step. Here you can review any action items which will help you get the most out of the SageGrouply platform.',//content. Could be text or html. (if html set html attribute above to be true)
							image: '',//specify image to be shown on mobile view
							position: 'right'//top, bottom, right, left
						},
						{
							element: '.tour-jobs',//specify the target selector by id or class #search or .header (defaults: body)
							title: 'Your Jobs',//title of the tour step
							content: 'When you have one or more jobs, they will show up here. Jobs can include past reference jobs you\'ve added or new jobs you\'ve opened on SageGroupy',//content. Could be text or html. (if html set html attribute above to be true)
							image: '',//specify image to be shown on mobile view
							position: 'top'//top, bottom, right, left
						},
						{
							element: '.tour-filter-jobs',//specify the target selector by id or class #search or .header (defaults: body)
							title: 'Filter Your Jobs',//title of the tour step
							content: 'You can filter jobs by their type or status by applying job filters here.',//content. Could be text or html. (if html set html attribute above to be true)
							image: '',//specify image to be shown on mobile view
							position: 'left'//top, bottom, right, left
						},
						{
							element: '.tour-notifications',
							title: 'Notifications',
							content: 'You can manage all of your notifications from here. Notifications include new messages, job invites, agency invites & SageGroupy admin notifications.',
							image: '',
							position: 'top'
						},
						{
							element: '.tour-notification-menu',
							title: 'Notification Actions',
							content: 'You can use the notification menu to manage your notifications. From this menu you can respond to users, reject or accept job invites, archive notifications etc.',
							image: '',
							position: 'left'
						}
					];

					if ($('.tour-edit-profile').is(":visible")) {
						/*
						tourSteps.push({
							element: '.tour-edit-profile',
							title: 'Edit Your Profile',
							content: 'To continue the tour, click the "Edit Profile" button to the left.',
							image: '',
							position: 'right'
						})
						*/
					}

					window._tour.steps(tourSteps);

					window._tour.startTour();//initialize the tour
				}, 2000);

			});
        }



    </script>
@endsection
