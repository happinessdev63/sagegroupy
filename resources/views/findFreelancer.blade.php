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
<div class="row">
    <div class="col-lg-12 col-sm-12 padding-bottom-20 tour-agencies" id='search_option'>
      <md-whiteframe md-elevation="3" class="padding-4 bg-white">
        <!-- <div class="text-black font-weight-400">
            <label class="md-title md-primary">Search for </label>
            <md-radio class="md-primary" v-model="type1" id="type1" name="type1" md-value="freelancer">Freelancer</md-radio>
            <md-radio class="md-primary" v-model="type2" id="type2" name="type1" md-value="client">Client</md-radio>
            <md-radio class="md-primary" v-model="type3" id="type3" name="type1" md-value="client_freelancer">Both</md-radio>
        </div> -->
        <md-tabs>
          <md-tab id="freelancer" md-label="Freelancer" md-icon="perm_contact_calendar">
            <div>
                <sage-freelancer-list ></sage-freelancer-list>
            </div>
          </md-tab>
          <md-tab id="job" md-label="Job" md-icon="work" :md-active="'{{ $user->role}}' === 'freelancer'">
            <div>
                <sage-admin-jobs ref="admin-users"></sage-admin-jobs>
            </div>
          </md-tab>
        </md-tabs>
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
</div>

@endsection

@section('first-scripts')
    @if (\Request::get('intent') == 'newJob' && Request::has('jobId') && $job instanceof \App\Job)
    <script>
		var addthis_share = {
			url: "{{ url('/job/' . $job->id) }}",
			title: "New Job On SageGroupy - {{ str_replace("\\","",@json_encode($job->title)) }}",
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
