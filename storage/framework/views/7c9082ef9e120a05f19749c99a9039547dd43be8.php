<?php $__env->startSection('header-scripts'); ?>
    <script>
        window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'findAgencies';
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

        <md-whiteframe md-elevation="3" class=" padding-20 bg-white">


            <sage-agencies-list></sage-agencies-list>


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