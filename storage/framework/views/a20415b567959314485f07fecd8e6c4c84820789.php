<?php $__env->startSection('header-scripts'); ?>
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
        window.Laravel.job = <?php echo json_encode( $job ); ?>;
		window.sageSource.curPage = 'viewJobs';
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <md-whiteframe md-elevation="3" class="padding-20 bg-white">

            <sage-create-job :job="<?php echo e($job->id); ?>"></sage-create-job>

            <div class="clearfix"></div>
        </md-whiteframe>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>