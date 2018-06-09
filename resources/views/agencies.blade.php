@extends('layouts.app')

@section('header-scripts')
    <script type="text/javascript">
    window.sageSource.curPage = 'viewAgencies';
    </script>
@endsection

@section('extras')
    @if (Auth::user() && Auth::user()->id)
        <sage-end-job ref="endJob"></sage-end-job>
    @endif

@endsection

@section('content')

        <div class="row">
            <div class="col-lg-12 col-sm-12 padding-bottom-20 tour-agencies" id='agencies'>
                <div class="pull-right">
                    <md-button href="/agencies/create" class="md-primary">
                        <i class="md-icon material-icons site-menu-icon md-theme-default">add</i>
                        Create Agency
                    </md-button>
                    <md-button href="/agencies" class="md-primary">
                        <i class="md-icon material-icons site-menu-icon md-theme-default">assignment_ind</i>
                        Join an Agency
                    </md-button>
                </div>
                <div class="clearfix"></div>
                <sage-agencies></sage-agencies>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center padding-bottom-20 hidden-sm">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Agencies Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="3697833055"
                     data-ad-format="auto"></ins>
                <script>
					(adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>

@endsection

@section('scripts')
    <script>
		window.sageSource.user = <?php echo json_encode( $user ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
    </script>
@endsection