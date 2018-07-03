@extends('layouts.app')

@section('header-scripts')
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'createAgency';
    </script>
@endsection

@section('extras')

    <sage-full-modal ref="createReferenceModal" name="createReferenceModal" title="Create a New Reference Job">
        <sage-create-reference-job :agency="shared.agency"></sage-create-reference-job>
    </sage-full-modal>

    <sage-full-modal ref="agencyFreelancerSearchModal" name="agencyFreelancerSearchModal" title="Search For Freelancers">
        <div class="text-center col-lg-12 visible-sm">
            <!-- Agencies Responsive -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6500659003306107"
                 data-ad-slot="3697833055"
                 data-ad-format="auto"></ins>
        </div>

        <div class="col-lg-10 col-sm-12">
            <md-whiteframe md-elevation="3" class="padding-20 bg-white">

                <sage-freelancer-list intent="agency-invite"></sage-freelancer-list>

                <div class="clearfix"></div>
            </md-whiteframe>
        </div>

        <div class="text-center col-lg-2 hidden-sm">
            <!-- Agencies Responsive -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6500659003306107"
                 data-ad-slot="3697833055"
                 data-ad-format="auto"></ins>
        </div>
    </sage-full-modal>

    @if (Auth::user() && Auth::user()->id)
        <!-- Chat Placeholder -->
    @endif

@endsection

@section('content')
        <md-whiteframe md-elevation="3" class="padding-20 bg-white">

            <sage-create-agency></sage-create-agency>

            <div class="clearfix"></div>
        </md-whiteframe>

@endsection

@section('scripts')
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
