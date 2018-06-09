@extends('layouts.app')

@section('header-scripts')
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
        window.Laravel.job = <?php echo json_encode( $job ); ?>;
		window.sageSource.curPage = 'viewJobs';
    </script>
@endsection

@section('content')
        <md-whiteframe md-elevation="3" class="padding-20 bg-white">

            <sage-create-job :job="{{ $job->id }}"></sage-create-job>

            <div class="clearfix"></div>
        </md-whiteframe>

@endsection
