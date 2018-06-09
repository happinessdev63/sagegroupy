@extends('layouts.app')

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

            <sage-admin-users ref="admin-users"></sage-admin-users>
        </div>
        <div class="clearfix"></div>

        <!-- Chat Placeholder -->

@endsection

@section('scripts')
    <script>
        window.sageSource.user = <?php echo json_encode( Auth::user() ); ?>;
    </script>
@endsection