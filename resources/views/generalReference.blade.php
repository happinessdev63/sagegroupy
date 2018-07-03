@extends('layouts.app')

@section('header-scripts')
    <script type="text/javascript">
		window.sageSource.curPage = 'viewJobs';
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.reference = <?php echo json_encode( $reference ); ?>;
		window.sageSource.user = <?php echo json_encode( \Auth::user() ?: [] ); ?>;
    </script>
@endsection

@section('extras')

@endsection

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div v-cloak>
                <md-whiteframe md-elevation="3" class="padding-20  bg-white">

                    <!-- Show client information to everyone but the client -->
                    @if ($reference->has_client)
                        <div>
                            <h4 class="no-margin-top">Reference Provider</h4>
                            <md-avatar class="md-large">
                                <img src="{{ $reference->client->avatar }}" alt="People">
                            </md-avatar>

                            <div class="pull-right text-left margin-bottom-20">
                                <a href="/profile/{{ $reference->client->id }}" class="link-primary">
                                <h5 class="margin-bottom-5 no-margin-top">{{ $reference->client->name }}</h5>
                                </a>
                                <span class="text-muted">Joined {{ $reference->user->nice_date }}</span> <br/>
                                <label class="label label-primary padding-5"> Reference </label>
                            </div>
                            <div class="clearfix"></div>
                            <div class="">
                                <ul class="list-group">
                                    @if (!empty($reference->client->country))
                                    <li class="list-group-item">
                                        <span class="font-weight-500">
                                            Location
                                        </span>
                                        <span class="pull-right text-right">
                                                {{ $reference->client->country }}, {{ $reference->client->city }}
                                        </span>
                                        <div class="clearfix"></div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <hr class="green-hr"/>
                        </div>
                    @endif

                        <div>
                            <h4 class="no-margin-top">Freelancer Details</h4>
                            <md-avatar class="md-large">
                                <img src="{{ $reference->user->avatar }}" alt="People">
                            </md-avatar>
                            <div class="pull-right text-left margin-bottom-20">
                                <a href="/profile/{{ $reference->user->id }}" class="link-primary">
                                <h5 class="margin-bottom-5 no-margin-top">{{ $reference->user->name}}</h5>
                                </a>
                                <span class="text-muted">Joined {{ $reference->user->nice_date }}</span> <br/>
                                <label class="label label-primary padding-5">Freelancer </label>
                            </div>
                            <div class="clearfix"></div>
                            <div class="">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Location
                                    </span>
                                        <span class="pull-right text-right">
                                            {{ $reference->user->country }}, {{ $reference->user->city }}
                                    </span>
                                        <div class="clearfix"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                </md-whiteframe>
            </div>

            <div class="text-center margin-bottom-10">
                <!-- Jobs Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="6209404182"
                     data-ad-format="auto"></ins>
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
                        <h3 class="no-margin-top no-padding-top margin-bottom-5 pull-left">{{ $reference->title }}</h3>
                        <div class="pull-right">
                            <span class="text-muted margin-top-10">Added {{ $reference->created_at->diffForHumans() }}</span><br/>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <hr/>

                <!-- Reference Details / Info -->
                <div class="col-xs-12 col-lg-12">
                    <h4>Reference Details</h4>
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
                        {!! nl2br(strip_tags($reference->description, "<br><p><b><em><i><strong><ul><li>"))   !!}
                    </div>
                    @if (!empty($reference->url) && !empty($reference->url_description))
                        <div class="margin-top-10">
                            <a class="link-primary" href="{{ $reference->url }}" target="_blank" rel="nofollow">Visit Related Link ({{ $reference->url_description }}</a>
                        </div>
                    @endif
                </div>

                <!-- Reference Reference From Client -->
                @if ($reference->has_review)
                    <div class="col-xs-12 col-lg-12 margin-top-10">
                        <h4>Reference From {{ $reference->client->name }}</h4>
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
                                <p class="margin-10">
                                    {!! nl2br(strip_tags($reference->review, "<br><p><b><em><i><strong><ul><li>"))   !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="clearfix"></div>


                <!-- Related Reference Files -->
                <div class="col-lg-12 margin-top-10">
                    <h4 class="margin-bottom-20">Related Files & Media</h4>
                    <hr class="green-hr margin-top-5"/>

                    @if (count($reference->media) > 0 && \Auth::user())
                        @foreach($reference->media as $media)
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
                                    You must be logged in to view files and media associated with this reference.
                                @else
                                    There are no files or media associated with this reference yet.
                                @endif
                            </h5>
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
            </md-whiteframe>
        </div>
    </div>

    @if (\Request::get('intent') == 'clientReference' && !\Auth::user() && empty($reference->client_id))
        <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceModal" v-cloak>

            <p>You were invited to provide a reference for {{ $reference->user->name }}.</p><br/>
            <p>If you already have an account, please sign in. If you do not have an account please create a new account to continue, it will only take a second. </p>
            <hr class="green-hr"/>
            <div class="text-center">
                <md-button href="/login?r={{ url('/generalReference/' . $reference->id . "?intent=clientReference") }}" class="md-primary md-raised md-adj-width">
                    Sign In
                </md-button>
                <md-button href="/register?r={{ url('/generalReference/' . $reference->id . "?intent=clientReference") }}" class="md-primary md-raised md-adj-width">
                    Create An Account
                </md-button>
            </div>

        </sage-modal>
    @endif

    @if (\Request::get('intent') == 'clientReference' && \Auth::user() && empty($reference->client_id))
        <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceFullModal" v-cloak>

            <p>{{ $reference->user->name }} invited you to provide a reference for their SageGroupy profile.</p> <br/>
            <p>Please write a short note about your experience or affiliation with this freelancer. </p><br/>
            <p>Keep in mind your reference will be displayed on their public profile.</p><br/>

            <div>
                <div>
                    <md-input-container class="margin-top-20" v-bind:class="{ 'md-input-invalid' : errors.review  }">
                        <label>Please enter a reference for this user.</label>
                        <md-textarea v-model="shared.reference.review" required></md-textarea>
                        <span v-if="errors.review" class="md-error">@{{ errors.review[0] }}</span>
                    </md-input-container>
                </div>

                <div class="margin-top-20 text-right">
                    <md-button class="md-primary pull-right" @click="saveGeneralReference">Save Reference</md-button>
                </div>
            </div>
            </div>

        </sage-modal>
    @endif

@endsection

@section('scripts')
    <script>
		window.sageSource.user = <?php echo json_encode( $user ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
    </script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
(adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endsection
