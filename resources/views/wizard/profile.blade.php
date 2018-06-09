@extends('layouts.app')

@section('header-scripts')
    <script>
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
    </script>
@endsection

@section('extras')
    <sage-full-modal ref="createReferenceModal" name="createReferenceModal" title="Create a New Reference Job">
        <sage-create-reference-job></sage-create-reference-job>
    </sage-full-modal>

    <sage-full-modal ref="createGeneralReferenceModal" name="createGeneralReferenceModal" title="Create a New General Reference">
        <sage-create-general-reference ref="generalReferenceEditor"></sage-create-general-reference>
    </sage-full-modal>

    <sage-full-modal ref="createImportedReferenceModal" name="createImportedReferenceModal" title="Import a Reference">
        <sage-create-imported-reference ref="importedReferenceEditor"></sage-create-imported-reference>
    </sage-full-modal>

@endsection

@section('content')

        <div class="row">
            <div class="col-lg-12 col-sm-12 padding-bottom-10 ">
                <div class="bg-white border-radius-5 margin-bottom-20 md-whiteframe-3dp padding-20" v-cloak>
                    <h1 class="text-primary margin-top-5 font-size-28">{{ $user->first_name }}, Set Up Your SageGroupy Account</h1>
                    <md-progress :md-progress="shared.profile.percent_complete_score" class="margin-bottom-5">
                    </md-progress>
                    <span class="text-muted margin-top-5 tour-profile-complete">Your profile is @{{ shared.profile.percent_complete_score }}% complete.</span>
                    <hr class="text-muted margin-bottom-5"/>
                    <sage-profile-wizard :profile-id="{{\Auth::user()->id}}"></sage-profile-wizard>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
		window.sageSource.user = <?php echo json_encode( $user ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
    </script>
@endsection