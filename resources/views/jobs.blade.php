@extends('layouts.app')

@section('header-scripts')
    <script type="text/javascript">
    window.sageSource.curPage = 'viewJobs';
    </script>
@endsection

@section('extras')
    @if (Auth::user() && Auth::user()->id)
        <sage-end-job ref="endJob"></sage-end-job>
    @endif
@endsection

@section('content')
        <div class="row">
            <div class="col-lg-12 col-sm-12 tour-jobs" id='jobs'>
                @if (\Auth::user() && \Auth::user()->is_freelancer)
                <div class="pull-right">
                    <md-button href="/jobs/createReference" class="md-primary">
                        <i class="md-icon material-icons site-menu-icon md-theme-default">group_add</i>
                        Create Reference Job
                    </md-button>
                </div>
                <div class="clearfix"></div>
                @endif
                <sage-jobs ref="jobs"></sage-jobs>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center padding-bottom-20 hidden-sm">

                <!-- Jobs Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="6209404182"
                     data-ad-format="auto"></ins>
            </div>
        </div>

@endsection

@section('scripts')
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    <script>
		window.sageSource.user = <?php echo json_encode( $user ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
    </script>
@endsection
