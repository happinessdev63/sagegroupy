<?php $__env->startSection('content'); ?>

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

            <sage-admin-jobs ref="admin-users"></sage-admin-jobs>
        </div>
        <div class="clearfix"></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>