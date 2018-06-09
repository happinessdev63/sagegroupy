<!-- @todo Fix job view when a client has invited an agency to the job and there is no associated freelancer
ErrorException in d46e35a1da97becfd472afc95b7ad98fe252c7bf.php line 193:
Trying to get property of non-object (View: /vagrant/saje/resources/views/jobs/index.blade.php)
-->
@extends('layouts.app')

@section('title')
    <title>{{ $job->title }} - View Job - SageGroupy</title>
@endsection

@section('header-scripts')
    <script>
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.job = <?php echo json_encode( $job ); ?>;
		window.sageSource.user = <?php echo json_encode( \Auth::user() ?: [] ); ?>;
		window.sageSource.curPage = 'viewJobs';
    </script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-3">
        <sage-job-nav job="{{ $job->id }}"></sage-job-nav>
        <div class="text-center padding-10">
            <!-- Public users only (not logged in) -->
            @if(!$user)
                <md-button class="md-primary md-raised no-margin-right no-margin-left" href="/register?r={{ Request::url() }}">Join SageGroupy</md-button>
            @endif

        <!-- Only for clients and when job does not have a freelancer associated -->
            @if ($user && $user->id == $job->client_id && empty($job->freelancer_id))
                <md-button href="/search" class="md-primary md-raised no-margin-right no-margin-left btn-block"><md-icon class="font-size-14">search</md-icon> Search For Freelancers</md-button>
            @endif

            @if ($user && $user->id == $job->client_id && $job->hasClient())
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" href="/jobs/edit/{{ $job->id }}">
                    <md-icon class="font-size-14">edit</md-icon>
                    Edit This Job
                </md-button>
            @endif


        @if ($user && $user->id != $job->client_id && $job->hasClient() && $job->type != 'reference')
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" @click.native='contactUser(shared.job.client)'>
                    <md-icon class="font-size-14">chat</md-icon>
                    Contact {{ $job->client->name }}
                </md-button>
            @endif



            @if ($user && $user->id != $job->freelancer_id && $job->hasFreelancer() && $job->type != 'reference')
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" @click.native='contactUser(shared.job.freelancer)'>
                    <md-icon class="font-size-14">chat</md-icon>
                    Contact {{ $job->freelancer->name }}
                </md-button>
            @endif
        </div>
        <div class="text-center margin-bottom-10">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Jobs Responsive -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6500659003306107"
                 data-ad-slot="6209404182"
                 data-ad-format="auto"></ins>
            <script>
				(adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>

    </div>

    <div class="col-md-9">
        <md-whiteframe md-elevation="3" class="padding-20 margin-bottom-40 bg-white">
            <div class="col-lg-12">
                <div class="v-cloak-show margin-top-50" v-cloak>
                    <div class="animated-background margin-top-50">
                        <div class="background-masker content-long"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-medium"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-short"></div>
                        <div class="background-masker content-spacer"></div>
                    </div>
                </div>

                <div>
                    <h3 class="no-margin-top no-padding-top margin-bottom-5 pull-left">{{ $job->title }}</h3>
                    <div class="pull-right">
                        @if ($job->type != "reference")
                            <span class="text-muted margin-top-10">Posted {{ $job->created_at->diffForHumans() }}</span><br/>
                            <label class="label label-primary  margin-top-10 padding-5">Payment: {{ $job->nicePaymentTerms }} </label>
                        @else
                            <span class="text-muted margin-top-10">Created {{ $job->created_at->diffForHumans() }}</span><br/>
                            <label class="label label-primary  margin-top-10 padding-5">Reference Job </label>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>

            <hr/>

        <!-- Job Details / Info -->
            <div class="col-xs-12 col-lg-12">
                <h4>Job Details</h4>
                <hr class="green-hr margin-top-5"/>
                <div class="v-cloak-show margin-top-50" v-cloak>
                    <div class="animated-background margin-top-50">
                        <div class="background-masker content-long"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-medium"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-short"></div>
                        <div class="background-masker content-spacer"></div>
                    </div>
                </div>
                <div v-cloak>
                    {!! nl2br(strip_tags($job->description, "<br><p><b><em><i><strong><ul><li>"))   !!}
                </div>
            </div>

            <!-- Job Reference From Client -->
            @if ($job->hasReferences())
                @foreach($job->references()->where('type','client')->get() as $reference)
                    <div class="col-xs-12 col-lg-12 margin-top-10">
                        <h4>Client Feedback From {{ $reference->client->name }}</h4>
                        <hr class="green-hr margin-top-5"/>
                        <div class="v-cloak-show margin-top-50" v-cloak>
                            <div class="animated-background margin-top-50">
                                <div class="background-masker content-long"></div>
                                <div class="background-masker content-spacer-small"></div>
                                <div class="background-masker content-medium"></div>
                                <div class="background-masker content-spacer-small"></div>
                                <div class="background-masker content-short"></div>
                                <div class="background-masker content-spacer"></div>
                            </div>
                        </div>
                        <div v-cloak>
                            <md-avatar class="md-large pull-left margin-10">
                                <img src="{{ $reference->client->avatar }}" alt="Client">
                            </md-avatar>
                            <div class="margin-left-10 margin-top-20 padding-top-20">
                                <sage-view-rating  :rating="{{ $reference->rating }}"></sage-view-rating>
                                <p class="margin-10">
                                    {!! nl2br(strip_tags($reference->review, "<br><p><b><em><i><strong><ul><li>"))   !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="clearfix"></div>


            <!-- Related Job Files -->
            <div class="col-lg-12 margin-top-10">
                <h4 class="margin-bottom-20">Related Files & Media</h4>
                <hr class="green-hr margin-top-5"/>

                @if (count($job->media) > 0 && \Auth::user())
                    @foreach($job->media as $media)
                        <div class="col-lg-4 col-md-6 col-sm-12 margin-top-10" v-cloak>
                            <md-card md-with-hover>
                                <md-card-header>
                                    <div class="text-strong  font-size-16">{{ str_limit($media->pivot->name, 18) }}</div>
                                    <div class="md-subhead">{{ $media->created_at->diffForHumans() }}</div>
                                </md-card-header>

                                <md-card-media md-ratio="16:9">
                                    @if ($media->aggregate_type == "image")
                                        <a class="sageImage" href="{{ $media->getUrl() }}" rel="image-files">
                                            <img src="{{ $media->getUrl() }}" alt="{{ $media->pivot->name }}">
                                        </a>
                                    @else
                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                            <img src="/img/icons-download-file.png" alt="{{ $media->pivot->name }}">
                                        </a>
                                    @endif
                                </md-card-media>

                                <md-card-content>
                                    {{ strtoupper($media->extension) }} File <br/> {{ $media->pivot->description }}
                                </md-card-content>
                            </md-card>
                        </div>
                    @endforeach
                @else
                    <div class="text-center well">
                        <h5 class="font-size-16">
                            <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                            <br/>
                            @if (!\Auth::user())
                                You must be logged in to view files and media associated with this job.
                            @else
                                There are no files or media associated with this job yet.
                            @endif
                        </h5>
                    </div>
                @endif
            </div>
            <div class="clearfix"></div>
        </md-whiteframe>

        <!-- Show Related Invites -->
        @if ($job->invites->count() > 0 && $user && $user->id == $job->client_id)
            <md-whiteframe md-elevation="3" class="padding-20 margin-bottom-40 bg-white">
                <h4 class="no-margin-top no-padding-top margin-bottom-5">Invite History</h4>
                <hr/>
                <md-list class="custom-list md-triple-line margin-top-20">
                    @foreach ($job->invites as $invite)
                        @if ($invite->freelancer)
                        <md-list-item>
                            <img src="{{ $invite->freelancer->avatar }}" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                            <div class="md-list-text-container margin-top-10 padding-bottom-5">
                                <div>
                                    <span class="label label-info pull-right margin-right-20 no-margin-xs">{{ ucfirst($invite->status) }}</span>
                                    <a href="/profile/{{ $invite->freelancer->id }}" class="link-primary">Invite Sent to {{ $invite->freelancer->name  }}</a></span>
                                </div>
                                <span>{{ $invite->created_at->diffForHumans() }}</span>
                            </div>
                            <md-divider class="md-inset"></md-divider>
                        </md-list-item>
                        @endif
                            @if ($invite->agency)
                                <md-list-item>
                                    <img src="{{ $invite->agency->avatar }}" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                                    <div class="md-list-text-container margin-top-10 padding-bottom-5">
                                        <div>
                                            <span class="label label-info pull-right margin-right-20 no-margin-xs">{{ ucfirst($invite->status) }}</span>
                                            <a href="/agency/{{ $invite->agency->id }}" class="link-primary">Invite Sent to {{ $invite->agency->name  }}</a></span>
                                        </div>
                                        <span>{{ $invite->created_at->diffForHumans() }}</span>
                                    </div>
                                    <md-divider class="md-inset"></md-divider>
                                </md-list-item>
                            @endif
                    @endforeach
                </md-list>
            </md-whiteframe>
        @endif

    </div>
</div>
    <sage-end-job ref="endJob"></sage-end-job>
    @if (Auth::user())
        <!-- Chat Placeholder -->
    @endif

    @if (\Request::get('intent') != 'clientReference')
        <sage-cta delay="30000"></sage-cta>
    @endif

@if (\Request::get('intent') == 'clientReference' && !\Auth::user() && empty($job->client_id))
    <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceModal" v-cloak>

        <p>You were invited to provide a reference for this job by @if ($job->agency){{ $job->agency->name }} @else {{ $job->freelancer->name }} @endif </p>
        <p>If you already have an account, please sign in. If you do not have an account please create a new account to continue, it will only take a second. </p>
        <hr class="green-hr"/>
        <div class="text-center">
            <md-button href="/login?r={{ url('/job/' . $job->id . "?intent=clientReference") }}" class="md-primary md-raised md-adj-width">
                Sign In
            </md-button>
            <md-button href="/register?r={{ url('/job/' . $job->id . "?intent=clientReference") }}" class="md-primary md-raised md-adj-width">
                Create An Account
            </md-button>
        </div>

    </sage-modal>
@endif

@if (\Request::get('intent') == 'clientReference' && \Auth::user() && empty($job->client_id))
    <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceFullModal" v-cloak>

        <p>@if ($job->agency) {{ $job->agency->name }} @else {{ $job->freelancer->name }} @endif  invited you to provide a reference for this job.</p>
        <p>Please write a short note about your experience with this @if ($job->agency) agency. @else freelancer. @endif </p><p>Keep in mind your feedback will be displayed on their public profile.</p>

        <div >
            <sage-job-feedback></sage-job-feedback>
        </div>

    </sage-modal>
@endif

@endsection

@section('scripts')
    <script type="text/javascript">
		$(document).ready(function () {
			$(".sageImage").fancybox();
		});
    </script>
@endsection
