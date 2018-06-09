@extends('layouts.app')

@section('header-scripts')
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
        window.sageSource.user = <?php echo json_encode( $user ); ?>;
        window.sageSource.curPage = 'search';
    </script>
@endsection

@section('extras')
    @if (\Request::get('intent') == 'newJob' && Request::has('jobId') && $job instanceof \App\Job)
        <sage-modal :delay="1300" title="Share Your Job & Invite Freelancers" :show-logo="true" name="'newAgencyCreated'" v-cloak>

            <p>{{ \Auth::user()->first_name }}, now that you have created a new job, search for freelancers and share the job with your existing network.</p>
            <br/>
            <p>Your newly created job is publically viewable, which allows you to share it with your outside network and get more exposure.  </p>
                <md-input-container class="margin-top-10">
                    <label>Your Job URL</label>
                    <md-input value="{{ url('/job/' . $job->id) }}"></md-input>
                </md-input-container>

            <div class="text-center shareButtonHolder">

            </div>

        </sage-modal>

    @endif
@endsection

@section('content')

    <div class="text-center col-lg-12 visible-sm">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Search Responsive -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6500659003306107"
             data-ad-slot="9113811268"
             data-ad-format="auto"></ins>
        <script>
			(adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>

    <div class="col-lg-10 col-sm-12">
        <md-whiteframe md-elevation="3" class="padding-20 bg-white">
            <sage-freelancer-list ></sage-freelancer-list>

            <div class="clearfix"></div>
        </md-whiteframe>
        @if (\Request::get('intent') == 'newJob' && Request::has('jobId') && $job instanceof \App\Job)
        <md-button class="margin-top-10 addthis_button md-primary md-raised shareButton"
                   data-url="{{ url('/job/' . $job->id) }}"
                   data-title="New Job On SageGroupy - {{ $job->title }}"
                   data-description="{{ $job->short_description }}">
            <i class="md-icon material-icons site-menu-icon md-theme-default">share</i>
            Share Your Job
        </md-button>
        @endif
    </div>

    <div class="text-center col-lg-2 hidden-sm">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Search Responsive -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6500659003306107"
             data-ad-slot="9113811268"
             data-ad-format="auto"></ins>
        <script>
			(adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>

@endsection

@section('first-scripts')
    @if (\Request::get('intent') == 'newJob' && Request::has('jobId') && $job instanceof \App\Job)
    <script>
		var addthis_share = {
			url: "{{ url('/job/' . $job->id) }}",
			title: "New Job On SageGroupy - {{ str_replace("\"","",@json_encode($job->title)) }}",
			description: "{{ @json_encode($job->short_description) }}",
		}
    </script>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".sageImage").fancybox();

            /* Share button is NOT working when rednered in modal, isntead we will render it on page and move it to the modal */
			$('.shareButton').hide();

			setTimeout(function () {
				$('.shareButton').detach().appendTo('.shareButtonHolder').show();
			}, 1300);
        });

    </script>
@endsection