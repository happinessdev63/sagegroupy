
<?php $activeModal = \Auth::user()->getActiveModalsAttribute() ?>

<!-- Agency reminder modal -->
@if ($activeModal ==  'agenciesReminder')
<sage-modal :delay="3300" title="Create or Join Agencies" :show-logo="true" name="agencyReminderModal" v-cloak>

    <p>@{{ shared.user.first_name }}, we have noticed that you have not created or joined any agencies.</p><br/> Agencies are a great way to take on larger projects
    and expand your client reach. You can create a new agency and invite other freelancers to join, or you can join an existing agency. </p>

    <div class="text-center">
        <md-button href="/agencies/create" class="md-primary md-raised md-adj-width">
            Create an Agency
        </md-button>
        <md-button href="agencies" class="md-primary md-raised md-adj-width">
            Join an Agency
        </md-button>
    </div>

</sage-modal>
@endif

<!-- Profile reminder modal -->
@if ($activeModal ==  'profileReminder')
<sage-modal :delay="3300" title="Complete Your Profile" :show-logo="true" name="profileReminderModal" v-cloak>

    <p>@{{ shared.user.first_name }}, you have not completed your profile yet. We highly recommend you complete your profile for the best experience on SageGroupy.  </p>

    <div class="text-center">
        <md-button href="/profile/edit/{{ \Auth::user()->id }}" class="md-primary md-raised md-adj-width">
            Edit My Profile
        </md-button>
    </div>

</sage-modal>
@endif

<!-- Reference jobs modal -->
@if ($activeModal ==  'referencesReminder')
<sage-modal :delay="3300" title="Add Previous Jobs to Your Profile" :show-logo="true" name="referencesReminderModal" v-cloak>

    <p>@{{ shared.user.first_name }}, you have not added any reference jobs to your profile.</p><br/> Reference jobs showcase your skills and help build trust with potential clients. We recommend adding as many high quality reference jobs as possible. </p><br/>You can also invite previous clients to rate your reference jobs & provide public feedback.</p>

    <div class="text-center">
        <md-button href="/jobs/createReference" class="md-primary md-raised md-adj-width">
            Create Reference Job
        </md-button>
    </div>

</sage-modal>
@endif

<!-- Jobs modal -->
@if ($activeModal ==  'jobsReminder')
<sage-modal :delay="3300" title="Create a New Job" :show-logo="true" name="jobsReminderModal" v-cloak>

    <p>@{{ shared.user.first_name }}, you have not creted any jobs yet! </p><br/><p>To get started with SageGroupy create a new job then search for freelancers and invite them to your job. The whole process only takes a few minutes and you can have your next project completed quickly & efficiently.</p>
    <br/>
    <p>Remember, there are no hidden fees for creating jobs, it's completely free!</p>

    <div class="text-center">
        <md-button href="/jobs/create" class="md-primary md-raised md-adj-width">
            Create a New Job
        </md-button>
    </div>

</sage-modal>
@endif
