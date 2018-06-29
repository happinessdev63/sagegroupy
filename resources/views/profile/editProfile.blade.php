@extends('layouts.app')

@section('header-scripts')
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
        window.sageSource.curPage = 'editProfile';
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
        <div class="col-md-3">
            <div class="md-whiteframe-3dp  bg-white padding-10">
                <div class="text-center margin-top-10" v-cloak>
                    <md-avatar class="md-x-large">
                        <img :src="shared.profile.avatar" alt="{{ $user->name }}">
                    </md-avatar>
                    @if (\Auth::user() && \Auth::user()->id == $user->id)
                        <br/>
                        <md-button @click="$root.emitEvent('editAvatar')" class="md-transparent font-size-10 margin-left-5">Edit Avatar</md-button>
                    @endif
                    <h3 class="margin-top-10">{{ $user->name }}</h3>
                    <h5>{{ $user->tagline }}</h5>
                    <p>{{ $user->city }}, {{ $user->country }}</p>

                    @if ($user->isFreelancer)
                        <div>
                            @foreach ($user->skills->take(8) as $skill)
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">{{ $skill->name }}</span>
                            @endforeach

                            @if (count($user->skills) == 0)
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">No Skills Added Yet</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="padding-10 text-center">
                <sage-become-client></sage-become-client>
                <sage-become-freelancer></sage-become-freelancer>
                <sage-share-profile-url></sage-share-profile-url>
            </div>

            <div class="text-center margin-bottom-10">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Profiles Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="1323909011"
                     data-ad-format="auto"></ins>
                <script>
					(adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>

        <div class="col-md-9">
            <div class="md-whiteframe-3dp bg-white padding-10">
                <div class="clearfix"></div>
                <sage-edit-profile :profile-id="{{\Auth::user()->id}}"></sage-edit-profile>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.sageSource.user = <?php echo json_encode( \Auth::user() ); ?>;
        window.sageSource.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'editProfile';
    </script>
@endsection
