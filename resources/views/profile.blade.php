@extends('layouts.app')

@section ('title')
    <title>View {{ $user->name }} Profile on SageGroupy, The Freelancer's Friend </title>
@show

@section('header-scripts')
    <script>
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.user = <?php echo json_encode( Auth::user() ?: [] ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'viewProfile';
    </script>
@endsection

@section('extras')
    @if (Auth::user() && Auth::user()->id)
        <!-- Chat Placeholder -->
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="md-whiteframe-3dp  bg-white padding-10">
                <div class="text-center margin-top-10" v-cloak>
                    <md-avatar class="md-x-large">
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                    </md-avatar>

                    <h3 class=" ">{{ $user->name }}</h3>
                    <h5>{{ $user->tagline }}</h5>
                    <p>{{ $user->city }}, {{ $user->country }}</p>

                    @if ($user->isFreelancer)
                        <div>
                            @foreach ($user->skills->take(8) as $skill)
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">{{ $skill->name }}</span>
                            @endforeach

                            @if (count($user->skills) == 0)
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">No Skills Added Yet</span>
                            @else
                                <a @click="emitEvent('showUserSkills')" class="label label-default margin-right-10 margin-bottom-10 display-inline-flex padding-5">View More...</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="padding-10 text-center">
                @if (Auth::user() &&  Auth::user()->id != $user->id)
                    <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click.native='contactUser(shared.profile)'>Contact {{ $user->firstName }}</md-button>
                    @if (\Auth::user() && Auth::user()->isAdmin)
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" target="_blank" href="/profile/postPdf/{{ $user->id }}">Download Profile</md-button>
                    @endif
                    @if (\Auth::user() && Auth::user()->isClient && $user->isFreelancer)
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="emitEvent('openInvite', {{ $user }})">Invite {{ $user->firstName }} to a Job</md-button>
                    @endif
                    @if ($user->isFreelancer && Auth::user() && Auth::user()->isFreelancer )
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="emitEvent('openAgencyInvite', {{ $user }})">Invite {{ $user->firstName }} to Your Agency</md-button>
                    @endif
                @else
                    <sage-share-profile-url></sage-share-profile-url>
                @endif
            </div>

            <div class="text-center margin-bottom-10">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Profiles Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="1323909011"
                     data-ad-format="auto"></ins>
                <script>
					(adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>

        <div class="col-md-9">
            <div class="md-whiteframe-3dp bg-white padding-10">
                <div class="clearfix"></div>

                <div class="col-lg-12 margin-bottom-15">
                    <div class="v-cloak-show margin-top-50" v-cloak>
                        <div class="animated-background margin-top-50">
                            <div class="background-masker content-long"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-medium"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-short"></div>
                            <div class="background-masker content-spacer"></div>
                        </div>
                        <div class="animated-background margin-top-50">
                            <div class="background-masker content-long"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-medium"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-short"></div>
                            <div class="background-masker content-spacer"></div>
                        </div>
                    </div>
                    <div id="profileDetails" v-cloak>

                        <h4 class="margin-top-10 margin-bottom-5">Profile Bio for {{ $user->name }}</h4>
                        <hr class="green-hr margin-top-5"/>
                        @if ($user->bio)
                            {!! nl2br(strip_tags($user->bio, "<br><p><b><em><i><strong><ul><li>"))   !!}
                        @else
                            <div class="text-center well">
                                <h4>
                                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                                </h4>
                                <p>Oops! It looks like {{ $user->firstName }} hasn't filled in all of their profile details yet. That doesn't mean they aren't awesome though. You can still contact this user directly to request additional details.</p>
                                <md-button class="md-primary md-raised" @click='contactUser(shared.profile)'>
                                                                              Contact {{ $user->firstName }}
                                </md-button>
                            </div>

                        @endif

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-lg-12 margin-top-10">
                    <h4 class="margin-bottom-10">Job References & Job History</h4>
                    <hr class="green-hr margin-top-5"/>
                    <sage-profile-references></sage-profile-references>
                </div>

                <div class="clearfix"></div>

                @if ($user->isFreelancer && $user->generalReferences->count() > 0)
                <div class="col-lg-12 margin-top-10">
                    <h4 class="margin-bottom-10">Additional References</h4>
                    <hr class="green-hr margin-top-5"/>
                    <md-list class="custom-list md-triple-line" v-cloak>
                        @foreach ($user->generalReferences as $reference )
                        <md-list-item>
                            @if (count($reference->files) > 0)
                            <div class="margin-right-20">
                                <img src="{{ $reference->files[0]['url'] }}" class="img-thumbnail sageImage" width="80" height="80" alt="Primary Image">
                            </div>
                            @else

                            <div class="md-icon-auto-height" style="min-width: 100px;">
                                <md-icon class="font-size-40">
                                    class
                                </md-icon>
                            </div>

                            @endif

                            <div class="md-list-text-container  margin-top-10">
                                <a class="font-weight-500 link-primary" href="/generalReference/{{ $reference->id }}">{{ $reference->title }}</a>
                                <span>Added {{ $reference->start_date }} </span>

                                @if ($reference->has_client && !empty($reference->review))
                                    <div>
                                        <div class="font-weight-200 margin-left-10 margin-top-5">
                                            <md-avatar class="pull-left margin-right-20">
                                                <img src="{{ $reference->client->avatar }}" alt="People">
                                            </md-avatar>
                                            <span class="font-weight-500">Reference from <a href="/profile/{{ $reference->client->id }}" target="_blank" class="link-primary margin-top-20">{{ $reference->client->name }}</a></span>
                                            <br/>
                                            <p class="margin-bottom-5">
                                                {!!  $reference->review  !!}
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                @else
                                    <div>
                                        {!! $reference->description !!}
                                        @if (!empty($reference->url) && !empty($reference->url_description))
                                        <div class="margin-top-10">
                                            <a class="link-primary" href="{{ $reference->url }}" target="_blank" rel="nofollow">Visit Related Link ({{ $reference->url_description }})</a>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <md-divider class="md-inset-110"></md-divider>
                        </md-list-item>
                        @endforeach
                    </md-list>
                </div>
                @endif

                <div class="clearfix"></div>

                <div class="col-lg-12 col-sm-12">
                    <h4>Agencies</h4>
                    <hr class="green-hr margin-top-5"/>
                    <sage-profile-agencies></sage-profile-agencies>
                    <div class="v-cloak-show margin-top-50" v-cloak>
                        <div class="animated-background margin-top-50">
                            <div class="background-masker content-long"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-medium"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-short"></div>
                            <div class="background-masker content-spacer"></div>
                        </div>
                        <div class="animated-background margin-top-50">
                            <div class="background-masker content-long"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-medium"></div>
                            <div class="background-masker content-spacer-small"></div>
                            <div class="background-masker content-short"></div>
                            <div class="background-masker content-spacer"></div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <hr class="margin-top-10"/>
                @if ($user->isFreelancer)
                    <sage-links></sage-links>
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
                @endif
                <div class="clearfix"></div>

                <div class="col-lg-12">
                <h4 class="margin-bottom-10 margin-top-20">Portfolio & Media</h4>
                <hr class="green-hr margin-top-5"/>
                @if (count($user->public_media) > 0)
                    @foreach($user->public_media as $media)

                    <!-- Only display public media files -->
                        @if (!$media->pivot->public)
                            @continue
                        @endif

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
                            This user has not added any portfolio or media items yet.
                        </h5>
                    </div>
                @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <sage-skills-modal :user="shared.profile"></sage-skills-modal>
    <sage-cta delay="3300"></sage-cta>
    <sage-agency-invite></sage-agency-invite>

@endsection

@section('scripts')
    <script type="text/javascript">
		$(document).ready(function () {
			$(".sageImage").fancybox();
		});
    </script>
@endsection
