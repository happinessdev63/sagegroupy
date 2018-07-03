<?php $__env->startSection('header-scripts'); ?>
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
        window.Laravel.job = <?php echo json_encode( $job ); ?>;
        window.sageSource.agency = <?php echo @json_encode( $agency ); ?>;
		window.sageSource.curPage = 'createReference';
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <md-whiteframe md-elevation="3" class="padding-20 bg-white">

        <sage-create-reference-job :job="<?php echo e($job->id); ?>" :agency="shared.agency"></sage-create-reference-job>

        <div class="clearfix"></div>
    </md-whiteframe>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>