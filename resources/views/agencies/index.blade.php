<?php

/**
 * @todo - Convert to vue components
 */

?>
@extends('layouts.app')

@section ('title')
    <title>{{ $agency->name }} - View Agency - SageGroupy</title>
@show

@section('header-scripts')
    <script type="text/javascript">
		window.sageSource.curPage = 'viewAgencies';
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
                        <img src="{{ $agency->avatar }}" alt="{{ $agency->name }}">
                    </md-avatar>

                    <h3 class=" ">{{ $agency->name }}</h3>

                    <h5>{{ $agency->location }}</h5>
                    <span class="label label-primary">
                    Agency With {{ $agency->freelancers()->count() + 1 }} {{ str_plural("Member", $agency->freelancers()->count() + 1) }}
                    </span>

                </div>
                <div class="padding-10 text-center">
                    @if (Auth::user()->id == $agency->owner_id)
                        <md-button href="/agencies/edit/{{ $agency->id }}" class="md-primary md-raised btn-block no-margin-left no-margin-right">Edit Agency</md-button>
                @endif
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click.native='contactUser(shared.agency.owner)'>Contact This Agency</md-button>
                    <sage-join-agency :agency-id="{{ $agency->id }}" type="button"></sage-join-agency>
                </div>
            </div>

            <div class="text-center">
                <!-- Agencies Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="3697833055"
                     data-ad-format="auto"></ins>
            </div>

        </div>

        <div class="col-md-9">
            <div class="md-whiteframe-3dp bg-white padding-10">
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

                <div class="clearfix"></div>

                <!-- Agency Members -->
                <div class="overflow-auto col-lg-12">
                    <h4 class="margin-bottom-10 margin-top-20">Members</h4>
                    <hr class="green-hr margin-top-5"/>
                    <table class="table table-striped  table-condensed" v-cloak>
                        <thead>
                            <th class="">Photo</th>
                            <th class="">Name</th>
                            <th class="">Location</th>
                            <th class="">Description</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="">
                                    <img :src="shared.agency.owner.avatar" class="img-thumbnail" style="max-width: 60px; max-height: 60px"
                                         :alt="shared.agency.owner.name">

                                </td>
                                <td class="">
                                    <a :href="shared.agency.owner.profile_link" class="link-primary" target="_blank">@{{ shared.agency.owner.name }}</a>
                                    <br/>
                                    <label class="label label-primary">Agency Leader</label>
                                </td>
                                <td class="">
                                    @{{ shared.agency.owner.city }}, @{{ shared.agency.owner.country }}
                                </td>
                                <td class="">
                                    @{{ shared.agency.owner.tagline }}
                                </td>
                            </tr>
                            <tr v-for="(freelancer, index) in shared.agency.freelancers">
                                <td class="">
                                    <img :src="freelancer.avatar" class="img-thumbnail" style="max-width: 60px; max-height: 60px" :alt="freelancer.name">
                                </td>
                                <td class="">
                                    <a :href="freelancer.profile_link" class="link-primary" target="_blank">@{{ freelancer.name }}</a>
                                    <br/>
                                    <label class="label label-primary">Member</label>
                                </td>
                                <td class="">
                                    @{{ freelancer.city }}, @{{ freelancer.country }}
                                </td>
                                <td class="">
                                    @{{ freelancer.tagline }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
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
                <hr class="margin-top-30"/>

                <!-- Agency Description -->
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
                <div  id="profileDetails" v-cloak>

                    @if ($agency->description)
                        <div class="col-lg-6 col-sm-12 ">
                            <div class="well">
                                <h4>Service Description</h4>
                                <hr class="green-hr"/>
                                {!! nl2br(strip_tags($agency->description, "<br><p><b><em><i><strong><ul><li><blockquote>"))   !!}
                            </div>
                        </div>
                    @endif
                    @if ($agency->description)
                        <div class="col-lg-6 col-sm-12">
                            <div class="well">
                                <h4>Company Description</h4>
                                <hr class="green-hr"/>
                                {!! nl2br(strip_tags($agency->company_description, "<br><p><b><em><i><strong><ul><li><blockquote>"))   !!}
                            </div>
                        </div>
                    @endif

                    @if (!$agency->description && !$agency->company_description)
                        <div class="text-center well">
                            <h4>
                                <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                            </h4>
                            <p>Oops! It looks like {{ $agency->name }} have not filled in all of their details yet. That doesn't mean they aren't awesome though. You can still contact this agency directly to request additional details.</p>
                        <!--
                        <md-button class="md-primary md-raised">
                            Contact {{ $agency->name }}
                                </md-button>
                                -->
                        </div>
                    @endif

                </div>
                <div class="clearfix"></div>
                <hr class=" margin-top-10 margin-left-15 margin-right-15"/>

                <!-- Agency Portfolio / Media Files -->
                <div class="margin-top-10 col-lg-12">
                    <h4 class="margin-bottom-10 margin-top-20">Portfolio & Media</h4>
                    <hr class="green-hr margin-top-5"/>
                @if (count($agency->media) > 0)
                    @foreach($agency->media as $media)

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
                                There are no files or media associated with this agency yet.
                            </h5>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                    <hr/>

                    <h4 class="margin-bottom-10 margin-top-20">Jobs & References</h4>
                    <hr class="green-hr margin-top-5"/>
                    <div class="text-center well" v-if="shared.agency.jobs.length == 0">
                        <h5 class="font-size-16">
                            <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                            <br/>
                            This agency has not added any reference jobs yet. <br/>You can contact them if you would like to see examples of their work.
                        </h5>
                    <!--
            <md-button class="md-primary md-raised">
                Contact {{ $agency->name }}
                            </md-button>
                            -->
                    </div>
                </div>

                <!-- Agency Jobs / References -->
                <div class="col-lg-12">
                    <md-list class="custom-list md-triple-line">
                        <md-list-item v-for="(job,index) in shared.agency.jobs">
                            <div v-if="primaryImageLink(job)" class="margin-right-20">
                                <img :src="primaryImageLink(job)" class="img-thumbnail" width="80" height="80" alt="Primary Image">
                            </div>

                            <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                                <md-icon class="font-size-40">
                                    assignment
                                </md-icon>
                            </div>


                            <div class="md-list-text-container margin-top-40 padding-bottom-5" v-cloak>
                                <div>
                            <span>
                                <span v-if="job.type == 'reference'"
                                      class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      Reference Job
                                </span>
                                <a :href="jobLink(job)" class="link-primary">@{{ job.title }}</a></span>
                                </div>
                                <span>Created @{{ job.start_date}}
                                    <span v-if="job.invite_client">, client was invited to provide a reference.</span>
                            <span v-if="job.completed"> - Completed @{{ job.end_date}}</span>
                        </span>
                                <div v-if="!job.completed">
                                    <p>Job is still in progress. </p>
                                </div>
                                <div v-if="job.references.length > 0 && job.references[0].review">
                                    <div class="pull-left">
                                        <sage-view-rating :rating="job.references[0].rating"></sage-view-rating>
                                    </div>
                                    <div class="pull-left font-weight-200 margin-left-10 margin-top-5 display-inline-block">
                                        Client rated agency @{{ job.references[0].rating }} out of 5
                                    </div>
                                    <div class="clearfix"></div>
                                    <br/>
                                    <p class="margin-bottom-5">
                                        @{{ job.references[0].review }}
                                    </p>
                                </div>
                                <p v-if="job.references.length == 0 || (!job.references[0].review && job.completed)">No review from the client yet.</p>
                            </div>

                            <div v-if="user.id == shared.agency.owner_id">
                                <md-button class="md-icon-button md-list-action">
                                    <md-icon class="md-primary">delete</md-icon>
                                </md-button>
                                <md-button class="md-icon-button md-list-action">
                                    <md-icon class="md-primary">edit</md-icon>
                                </md-button>
                            </div>

                            <md-divider class="md-inset-110"></md-divider>
                        </md-list-item>
                    </md-list>
                </div>
                <div class="clearfix"></div>
                <hr class=" margin-top-10 margin-left-15 margin-right-15"/>

                <!-- Agency Links -->
                <div class="col-lg-12">
                    <h4 class="margin-bottom-10 margin-top-20">Links</h4>
                    <hr class="green-hr margin-top-5"/>
                    <sage-links :agency="{{ $agency->id }}"></sage-links>
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
                </div>



                <div class="clearfix"></div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.sageSource.agency = <?php echo json_encode( $agency ); ?>;
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".sageImage").fancybox();
        });
    </script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
(adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endsection
