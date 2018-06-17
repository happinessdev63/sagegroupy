<!DOCTYPE html>
@section('html')
    <html lang="en">
        @show
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=780">

        @section ('meta')
            <!-- META -->
                <meta itemprop="name" content="Sage Groupy">
                <meta itemprop="url" content="http://sagegroupy.com"/>
                <meta name="description" itemprop="description" content="Sage Groupy">
                <meta name="author" content="SageGroupy">

                <meta property="og:url" content="http://sagegroupy.com"/>
                <meta property="og:type" content="website"/>
                <meta property="og:title" content="Sage Groupy Freelancers"/>
                <meta property="og:description" content="Sage Groupy Freelancers"/>
                <meta property="twitter:url" content="http://sagegroupy.com"/>
                <meta property="twitter:type" content="website"/>
                <meta property="twitter:title" content="Sage Groupy Freelancers"/>
                <meta property="twitter:description" content="Sage Groupy Freelancers"/>

                <meta property="og:image" content="{{ env("APP_URL")}}/img/icon_xxs.png"/>
                <meta property="twitter:image" content="{{ env("APP_URL")}}/img/icon_xxs.png"/>
            @show

            @section ('title')
                <title>SageGroupy</title>
            @show

        <!-- Fonts -->
            <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
            <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">

            <!-- Styles -->
            <link href="/css/app.css" rel="stylesheet" type="text/css">
            <link href="/js/plugins/jquery.fancybox.min.css" rel="stylesheet" type="text/css">
            <link href="/js/plugins/product-tour.min.css" rel="stylesheet" type="text/css">
            <link href="/css/vue-material.css" rel="stylesheet" type="text/css">

            <!-- Styles -->
            @section('header-styles')
            @show

        <!-- Scripts -->
            <!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <script>
				(adsbygoogle = window.adsbygoogle || []).push({
					google_ad_client: "ca-pub-6500659003306107",
					enable_page_level_ads: true
				});
            </script> -->
            <!-- Facebook Pixel Code -->
            <!-- <script>
				!function (f, b, e, v, n, t, s) {
					if (f.fbq)return;
					n = f.fbq = function () {
						n.callMethod ?
							n.callMethod.apply(n, arguments) : n.queue.push(arguments)
					};
					if (!f._fbq) f._fbq = n;
					n.push = n;
					n.loaded = !0;
					n.version = '2.0';
					n.queue = [];
					t = b.createElement(e);
					t.async = !0;
					t.src = v;
					s = b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t, s)
				}(window, document, 'script',
					'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '542388909257295');
				fbq('track', 'PageView');
            </script> -->
            <!-- <noscript>
                <img height="1" width="1"
                     src="https://www.facebook.com/tr?id=542388909257295&ev=PageView
&noscript=1"/>
            </noscript> -->
            <!-- End Facebook Pixel Code -->
            <script>
				window.Laravel = <?php echo json_encode( [
                    'csrfToken'    => csrf_token(),
                    'apiToken'     => Auth::user()->api_token ?? null,
                    '_token'       => csrf_token(),
                    'user'         => \Auth::check() ? Auth::user() : [],
                    'userShareUrl' => \Auth::check() ? Auth::user()->publicProfileUrl : 'Not Available',
                ] ); ?>;

                /* Data objects for the app */
				window.sageSource = {
					jobs: [],
                    references: [],
					user: {
						agencies: [],
						owned_agencies: [],
						client_jobs: [],
						freelancer_jobs: [],
						skills: [],
						references: [],
						recommendations: [],
						recommended_users: [],
						files: [],
						links: [],
					},
					profile: {
						city: "",
						agencies: [],
						owned_agencies: [],
						client_jobs: [],
						freelancer_jobs: [],
						skills: [],
						references: [],
						recommendations: [],
						recommended_users: [],
						links: [],
						files: []
					},
					file: [],
					link: {
						files: [],
					},
					defaultLink: {
						files: [],
					},
					errors: [],
					settings: {},
					skills: [],
					skill: {},
					defaultSkill: {
						name: '',
						level: 'Entry',
						rate: null,
						users_count: 0,
						avg_rates: [
							{
								"level": "Entry",
								"rate": null
							},
							{
								"level": "Junior",
								"rate": null
							},
							{
								"level": "Intermediate",
								"rate": null
							},
							{
								"level": "Senior",
								"rate": null
							},
							{
								"level": "Expert",
								"rate": null
							}
						]
					},
					agencies: [],
					freelancers: [],
					agency: {
						name: "",
						description: "",
						company_description: "",
						location: "",
						city: "",
						country: "",
						owner: {},
						jobs: {},
						freelancers: {},
						clients: {},
						references: {},
						files: [],
						links: [],
						avatar: '/img/avatar.jpg'
					},
					job: {
						freelancer: {},
						client: {},
						agency: {}
					},
					notifications: [],
					sidebarNotifications: [],
					chatMessages: [],
					unreadNotifications: 0,
					notification: {
						sender: {},
						receiver: {},
						agency: {},
						fromAgency: {},
						job: {}
					},
					defaultNotification: {
						sender: {},
						receiver: {},
						agency: {},
						fromAgency: {},
						job: {}
					},
					page: '',
					state: {
						show_icon_end_job: false,
                        menu_open: true,
                        window_width: 0,
                        window_height: 0,
                        chat_loaded: false
					},
					ratings: [
						{
							title: '5 Stars',
							value: 5
						},
						{
							title: '4 Stars',
							value: 4
						},
						{
							title: '3 Stars',
							value: 3
						},
						{
							title: '2 Stars',
							value: 2
						},
						{
							title: '1 Star',
							value: 1
						}
					],
                    curPage: 'dashboard'
				};
            </script>
            @section('header-scripts')
            @show

        </head>
        @section('body')
            <body class="bg-white @if (Auth::user()) site-menubar-unfold @endif">
                @show
                <div id="app">

                    <md-snackbar md-position="top center" ref="snackbar" md-duration="5000" v-cloak>
                        <span>@{{snackBarMessage}}</span>
                        <md-button class="md-accent" @click.native="$refs.snackbar.close()">Close</md-button>
                    </md-snackbar>

                    @section('nav')
                        <sage-nav current-page="dashboard"></sage-nav>
                        @if (Auth::user())
                        <div class="site-menubar">
                            <div class="site-menubar-body">
                                <div>
                                    <div>
                                        <ul class="site-menu">
                                            <li class="site-menu-category">Account</li>
                                            <li class="site-menu-item" :class="getNavClass('dashboard')">
                                                <a class="animsition-link" href="/dashboard">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">dashboard</i>
                                                    <span class="site-menu-title">Dashboard</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item tour-edit-profile" :class="getNavClass('editProfile')">
                                                <a class="animsition-link" href="{{ \Auth::user()->profileEditLink }}" >
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">person</i>
                                                    <span class="site-menu-title">Edit Profile</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item" :class="getNavClass('viewProfile')">
                                                <a class="animsition-link" href="/profile">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">visibility</i>
                                                    <span class="site-menu-title">View Profile</span>
                                                </a>
                                            </li>
                                        </ul>
                                        @if (\Auth::user()->is_freelancer)
                                        <ul class="site-menu">
                                            <li class="site-menu-category">Jobs</li>
                                            <li class="site-menu-item" :class="getNavClass('createReference')">
                                                <a class="animsition-link" href="/jobs/createReference">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">group_add</i>
                                                    <span class="site-menu-title">New Reference Job</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item" :class="getNavClass('viewJobs')">
                                                <a class="animsition-link" href="/userJobs">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">assignment</i>
                                                    <span class="site-menu-title">Your Jobs</span>
                                                    <span class="label label-primary pull-right margin-top-10">{{ Auth::user()->freelancerJobs->count() }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                        @endif

                                        @if (\Auth::user()->is_freelancer)
                                        <ul class="site-menu">
                                            <li class="site-menu-category">Agencies</li>
                                            <li class="site-menu-item" :class="getNavClass('createAgency')">
                                                <a class="animsition-link" href="/agencies/create">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">add</i>
                                                    <span class="site-menu-title">New Agency</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item" :class="getNavClass('findAgencies')">
                                                <a class="animsition-link" href="/agencies">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">assignment_ind</i>
                                                    <span class="site-menu-title">Join an Agency</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item" :class="getNavClass('viewAgencies')">
                                                <a class="animsition-link" href="/userAgencies" >
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">card_membership</i>
                                                    <span class="site-menu-title">Your Agencies</span>
                                                    <span class="label label-primary pull-right margin-top-10">{{ Auth::user()->agencies->count() + Auth::user()->ownedAgencies->count()}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                        @endif

                                        @if (\Auth::user()->is_client)
                                            <ul class="site-menu">
                                                <li class="site-menu-category">Hire</li>
                                                <li class="site-menu-item" :class="getNavClass('search')">
                                                    <a class="animsition-link" href="/search" >
                                                        <i class="md-icon material-icons site-menu-icon md-theme-default">assignment_ind</i>
                                                        <span class="site-menu-title">Find a Freelancer</span>
                                                    </a>
                                                </li>
                                                <li class="site-menu-item" :class="getNavClass('createJob')">
                                                    <a class="animsition-link" href="/jobs/create">
                                                        <i class="md-icon material-icons site-menu-icon md-theme-default">add</i>
                                                        <span class="site-menu-title">New Job</span>
                                                    </a>
                                                </li>
                                                <li class="site-menu-item" :class="getNavClass('viewJobs')">
                                                    <a class="animsition-link" href="/userJobs" >
                                                        <i class="md-icon material-icons site-menu-icon md-theme-default">assignment</i>
                                                        <span class="site-menu-title">Your Jobs</span>
                                                        <span class="label label-primary pull-right margin-top-10">{{ Auth::user()->clientJobs->count() }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif

                                        <ul class="site-menu">
                                            <li class="site-menu-category">Support</li>
                                            <li class="site-menu-item" :class="getNavClass('support')">
                                                <a class="animsition-link" href="/res/SagegroupyUserGuide.pdf" target="_blank">
                                                <i class="md-icon material-icons site-menu-icon md-theme-default">chrome_reader_mode</i>
                                                <span class="site-menu-title">User Guide</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item" :class="getNavClass('support')">
                                                <a class="animsition-link" href="javascript:void(0);" @click="openDialog('feedbackDialog')">
                                                    <i class="md-icon material-icons site-menu-icon md-theme-default">live_help</i>
                                                    <span class="site-menu-title">Feedback / Contact Us</span>
                                                </a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="site-menubar-footer">
                                <a href="{{ \Auth::user()->profileEditLink }}" class="fold-show" data-placement="top" data-toggle="tooltip"
                                   data-original-title="Settings">
                                    <i class="md-icon material-icons site-menu-icon md-theme-default">settings</i>
                                </a>
                                <a href="/search" data-placement="top" data-toggle="tooltip" data-original-title="Lock">
                                    <i class="md-icon material-icons site-menu-icon md-theme-default">search</i>
                                </a>
                                <a href="/logout" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
                                    <i class="md-icon material-icons site-menu-icon md-theme-default">power_settings_new</i>
                                </a>
                            </div>
                        </div>
                        @endif
                    @show

                    @section('extras')
                    @show

                    @if (Auth::user() && Auth::user()->id)
                        <sage-chat :owner="{{ Auth::user()->id }}"></sage-chat>
                    @endif

                    <div class="{{ $contentClass or 'content' }}">
                        @section('content')
                        @show

                        @section('inside-footer')
                        @show
                    </div>

                    <div class="clearfix"></div>

                    @section('outside-footer')
                    @show

                    <sage-invite-freelancer></sage-invite-freelancer>
                    @if (Auth::user() && Auth::user()->isAdmin())
                        <sage-admin-bulk-notify :owner="{{ Auth::user()->id }}"></sage-admin-bulk-notify>
                    @endif

                    <md-dialog ref="feedbackDialog" class="feedback-dialog">
                        <md-dialog-title>Provide Feedback / Contact Us</md-dialog-title>

                        <md-dialog-content>
                            <form>
                                <md-input-container>
                                    <label>Enter Your Questions or Comments</label>
                                    <md-textarea v-model="feedbackMessage"></md-textarea>
                                </md-input-container>
                            </form>
                        </md-dialog-content>

                        <md-dialog-actions>
                            <md-button class="md-primary" @click="closeDialog('feedbackDialog')">Cancel</md-button>
                            <md-button class="md-primary" @click="sendUserFeedback">Send</md-button>
                        </md-dialog-actions>
                    </md-dialog>
                </div>
            </body>



            @section('first-scripts')
            @show

            <script src="/js/app.js"></script>
            <script src="/js/plugins/jquery.fancybox.min.js"></script>
            <script src="/js/plugins/product-tour.min.js"></script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58f7a8bf66ec9acf"></script>
            <!-- <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                            (i[r].q = i[r].q || []).push(arguments)
                        }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                ga('create', 'UA-84063026-1', 'auto');
                ga('send', 'pageview');
            </script> -->
            <script>
				var vmBus = window.appBus;
				window.addthis.addEventListener('addthis.user.clickback', function (event) {
					vmBus.$emit('shareClickBack', event);
				});
				window.addthis.addEventListener('addthis.menu.share', function (event) {
					vmBus.$emit('sharedProfile', event);
				});
            </script>

        @section('scripts')
        @show
    </html>
