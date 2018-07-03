<?php $__env->startSection('header-styles'); ?>
    <link href="/css/sage.css" rel="stylesheet" type="text/css">
    <link href="/css/vue-material.css" rel="stylesheet" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <body style="background-image: url('/images/bg3.jpg') !important; background-size: cover !important;">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('nav'); ?>
    <?php echo $__env->make('lander.nav-external', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $contentClass = 'auth-content' ?>
<?php $__env->startSection('content'); ?>

    <md-whiteframe md-elevation="3" class="margin-top-55 text-center padding-50 col-lg-4 col-lg-offset-4 col-sm-12 bg-white-transparent">
        <div>
            <img src="/img/logo_xs.png"/>
            <h4 class="font-weight-300">Create a New Account</h4>
            <sage-register _token="<?php echo e(csrf_token()); ?>" redirect-url="<?php echo e(Request::get("r",Session::get('url.intended', url('/dashboard')))); ?>"></sage-register>
        </div>
        <div class="clearfix"></div>
    </md-whiteframe>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('outside-footer'); ?>
    <div class="margin-top-60">
        <?php echo $__env->make('lander.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>