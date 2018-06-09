@extends('layouts.app')

@section('header-scripts')
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'createReference';
    </script>
@endsection
@section('content')

        <md-whiteframe md-elevation="3" class="padding-20 bg-white">

            <sage-create-reference-job></sage-create-reference-job>

            <div class="clearfix"></div>
        </md-whiteframe>

@endsection
