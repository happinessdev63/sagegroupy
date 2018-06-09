@extends('layouts.app')

@section('title')
    <title>Admin Settings - SageGroupy</title>
@endsection

@section('content')

        <div class="col-lg-12">
            <!-- Cloaked Place Holder Loading Content -->
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
            <!-- End place holders -->

            <sage-admin-settings ref="admin-settings"></sage-admin-settings>
        </div>
        <div class="clearfix"></div>

@endsection

@section('scripts')
    <script type="text/javascript">
        window.sageSource.settings = <?php echo json_encode( $settings ); ?>;
    </script>
@endsection