<?php $__env->startSection('header-scripts'); ?>
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'viewAgencies';
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extras'); ?>

    <sage-full-modal ref="createReferenceModal" name="createReferenceModal" title="Create a New Reference Job">
        <sage-create-reference-job :agency="shared.agency"></sage-create-reference-job>
    </sage-full-modal>

    <sage-full-modal ref="agencyFreelancerSearchModal" name="agencyFreelancerSearchModal" title="Search For Freelancers">
        <div class="text-center col-lg-12 visible-sm">
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

        <div class="col-lg-10 col-sm-12">
            <md-whiteframe md-elevation="3" class="padding-20 bg-white">

                <sage-freelancer-list intent="agency-invite"></sage-freelancer-list>

                <div class="clearfix"></div>
            </md-whiteframe>
        </div>

        <div class="text-center col-lg-2 hidden-sm">
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
    </sage-full-modal>

    <?php if(\Request::get('intent') == 'created'): ?>
        <sage-modal :delay="1300" title="New Agency Created" :show-logo="true" name="'newAgencyCreated'" v-cloak>

            <p><?php echo e(\Auth::user()->first_name); ?>, your agency was created successfully.</p>
            <br/>
            <p>Using the agency management tabs below you can invite other freelancers to join your agency, add relevant jobs & media and add links to your agency profile. </p>

        </sage-modal>
    <?php endif; ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

        <md-whiteframe md-elevation="3" class="margin-top-20 padding-20 bg-white">

            <sage-create-agency :agency-id="<?php echo e($agency->id); ?>"></sage-create-agency>

            <div class="clearfix"></div>
        </md-whiteframe>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".sageImage").fancybox();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>