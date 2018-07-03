<!-- @todo  Fix job view when a client has invited an agency to the job and there is no associated freelancer
ErrorException in d46e35a1da97becfd472afc95b7ad98fe252c7bf.php line 193:
Trying to get property of non-object (View: /vagrant/saje/resources/views/jobs/index.blade.php)
-->


<?php $__env->startSection('title'); ?>
    <title><?php echo e($job->title); ?> - View Job - SageGroupy</title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-scripts'); ?>
    <script>
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.job = <?php echo json_encode( $job ); ?>;
		window.sageSource.user = <?php echo json_encode( \Auth::user() ?: [] ); ?>;
		window.sageSource.curPage = 'viewJobs';
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-3">
        <sage-job-nav job="<?php echo e($job->id); ?>"></sage-job-nav>
        <div class="text-center padding-10">
            <!-- Public users only (not logged in) -->
            <?php if(!$user): ?>
                <md-button class="md-primary md-raised no-margin-right no-margin-left" href="/register?r=<?php echo e(Request::url()); ?>">Join SageGroupy</md-button>
            <?php endif; ?>

        <!-- Only for clients and when job does not have a freelancer associated -->
            <?php if($user && $user->id == $job->client_id && empty($job->freelancer_id)): ?>
                <md-button href="/search" class="md-primary md-raised no-margin-right no-margin-left btn-block"><md-icon class="font-size-14">search</md-icon> Search For Freelancers</md-button>
            <?php endif; ?>

            <?php if($user && $user->id == $job->client_id && $job->hasClient()): ?>
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" href="/jobs/edit/<?php echo e($job->id); ?>">
                    <md-icon class="font-size-14">edit</md-icon>
                    Edit This Job
                </md-button>
            <?php endif; ?>


        <?php if($user && $user->id != $job->client_id && $job->hasClient() && $job->type != 'reference'): ?>
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" @click.native='contactUser(shared.job.client)'>
                    <md-icon class="font-size-14">chat</md-icon>
                    Contact <?php echo e($job->client->name); ?>

                </md-button>
            <?php endif; ?>



            <?php if($user && $user->id != $job->freelancer_id && $job->hasFreelancer() && $job->type != 'reference'): ?>
                <md-button class="md-primary md-raised no-margin-right no-margin-left btn-block" @click.native='contactUser(shared.job.freelancer)'>
                    <md-icon class="font-size-14">chat</md-icon>
                    Contact <?php echo e($job->freelancer->name); ?>

                </md-button>
            <?php endif; ?>
        </div>
        <div class="text-center margin-bottom-10">
            <!-- Jobs Responsive -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6500659003306107"
                 data-ad-slot="6209404182"
                 data-ad-format="auto"></ins>
        </div>

    </div>

    <div class="col-md-9">
        <md-whiteframe md-elevation="3" class="padding-20 margin-bottom-40 bg-white">
            <div class="col-lg-12">
                <div class="v-cloak-show margin-top-50" v-cloak>
                    <div class="animated-background margin-top-50">
                        <div class="background-masker content-long"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-medium"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-short"></div>
                        <div class="background-masker content-spacer"></div>
                    </div>
                </div>

                <div>
                    <h3 class="no-margin-top no-padding-top margin-bottom-5 pull-left"><?php echo e($job->title); ?></h3>
                    <div class="pull-right">
                        <?php if($job->type != "reference"): ?>
                            <span class="text-muted margin-top-10">Posted <?php echo e($job->created_at->diffForHumans()); ?></span><br/>
                            <label class="label label-primary  margin-top-10 padding-5">Payment: <?php echo e($job->nicePaymentTerms); ?> </label>
                        <?php else: ?>
                            <span class="text-muted margin-top-10">Created <?php echo e($job->created_at->diffForHumans()); ?></span><br/>
                            <label class="label label-primary  margin-top-10 padding-5">Reference Job </label>
                        <?php endif; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>

            <hr/>

        <!-- Job Details / Info -->
            <div class="col-xs-12 col-lg-12">
                <h4>Job Details</h4>
                <hr class="green-hr margin-top-5"/>
                <div class="v-cloak-show margin-top-50" v-cloak>
                    <div class="animated-background margin-top-50">
                        <div class="background-masker content-long"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-medium"></div>
                        <div class="background-masker content-spacer-small"></div>
                        <div class="background-masker content-short"></div>
                        <div class="background-masker content-spacer"></div>
                    </div>
                </div>
                <div v-cloak>
                    <?php echo nl2br(strip_tags($job->description, "<br><p><b><em><i><strong><ul><li>")); ?>

                </div>
            </div>

            <!-- Job Reference From Client -->
            <?php if($job->hasReferences()): ?>
                <?php $__currentLoopData = $job->references()->where('type','client')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xs-12 col-lg-12 margin-top-10">
                        <h4>Client Feedback From <?php echo e($reference->client->name); ?></h4>
                        <hr class="green-hr margin-top-5"/>
                        <div class="v-cloak-show margin-top-50" v-cloak>
                            <div class="animated-background margin-top-50">
                                <div class="background-masker content-long"></div>
                                <div class="background-masker content-spacer-small"></div>
                                <div class="background-masker content-medium"></div>
                                <div class="background-masker content-spacer-small"></div>
                                <div class="background-masker content-short"></div>
                                <div class="background-masker content-spacer"></div>
                            </div>
                        </div>
                        <div v-cloak>
                            <md-avatar class="md-large pull-left margin-10">
                                <img src="<?php echo e($reference->client->avatar); ?>" alt="Client">
                            </md-avatar>
                            <div class="margin-left-10 margin-top-20 padding-top-20">
                                <sage-view-rating  :rating="<?php echo e($reference->rating); ?>"></sage-view-rating>
                                <p class="margin-10">
                                    <?php echo nl2br(strip_tags($reference->review, "<br><p><b><em><i><strong><ul><li>")); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div class="clearfix"></div>


            <!-- Related Job Files -->
            <div class="col-lg-12 margin-top-10">
                <h4 class="margin-bottom-20">Related Files & Media</h4>
                <hr class="green-hr margin-top-5"/>

                <?php if(count($job->media) > 0 && \Auth::user()): ?>
                    <?php $__currentLoopData = $job->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 margin-top-10" v-cloak>
                            <md-card md-with-hover>
                                <md-card-header>
                                    <div class="text-strong  font-size-16"><?php echo e(str_limit($media->pivot->name, 18)); ?></div>
                                    <div class="md-subhead"><?php echo e($media->created_at->diffForHumans()); ?></div>
                                </md-card-header>

                                <md-card-media md-ratio="16:9">
                                    <?php if($media->aggregate_type == "image"): ?>
                                        <a class="sageImage" href="<?php echo e($media->getUrl()); ?>" rel="image-files">
                                            <img src="<?php echo e($media->getUrl()); ?>" alt="<?php echo e($media->pivot->name); ?>">
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e($media->getUrl()); ?>" target="_blank">
                                            <img src="/img/icons-download-file.png" alt="<?php echo e($media->pivot->name); ?>">
                                        </a>
                                    <?php endif; ?>
                                </md-card-media>

                                <md-card-content>
                                    <?php echo e(strtoupper($media->extension)); ?> File <br/> <?php echo e($media->pivot->description); ?>

                                </md-card-content>
                            </md-card>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center well">
                        <h5 class="font-size-16">
                            <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                            <br/>
                            <?php if(!\Auth::user()): ?>
                                You must be logged in to view files and media associated with this job.
                            <?php else: ?>
                                There are no files or media associated with this job yet.
                            <?php endif; ?>
                        </h5>
                    </div>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </md-whiteframe>

        <!-- Show Related Invites -->
        <?php if($job->invites->count() > 0 && $user && $user->id == $job->client_id): ?>
            <md-whiteframe md-elevation="3" class="padding-20 margin-bottom-40 bg-white">
                <h4 class="no-margin-top no-padding-top margin-bottom-5">Invite History</h4>
                <hr/>
                <md-list class="custom-list md-triple-line margin-top-20">
                    <?php $__currentLoopData = $job->invites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($invite->freelancer): ?>
                        <md-list-item>
                            <img src="<?php echo e($invite->freelancer->avatar); ?>" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                            <div class="md-list-text-container margin-top-10 padding-bottom-5">
                                <div>
                                    <span class="label label-info pull-right margin-right-20 no-margin-xs"><?php echo e(ucfirst($invite->status)); ?></span>
                                    <a href="/profile/<?php echo e($invite->freelancer->id); ?>" class="link-primary">Invite Sent to <?php echo e($invite->freelancer->name); ?></a></span>
                                </div>
                                <span><?php echo e($invite->created_at->diffForHumans()); ?></span>
                            </div>
                            <md-divider class="md-inset"></md-divider>
                        </md-list-item>
                        <?php endif; ?>
                            <?php if($invite->agency): ?>
                                <md-list-item>
                                    <img src="<?php echo e($invite->agency->avatar); ?>" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                                    <div class="md-list-text-container margin-top-10 padding-bottom-5">
                                        <div>
                                            <span class="label label-info pull-right margin-right-20 no-margin-xs"><?php echo e(ucfirst($invite->status)); ?></span>
                                            <a href="/agency/<?php echo e($invite->agency->id); ?>" class="link-primary">Invite Sent to <?php echo e($invite->agency->name); ?></a></span>
                                        </div>
                                        <span><?php echo e($invite->created_at->diffForHumans()); ?></span>
                                    </div>
                                    <md-divider class="md-inset"></md-divider>
                                </md-list-item>
                            <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </md-list>
            </md-whiteframe>
        <?php endif; ?>

    </div>
</div>
    <sage-end-job ref="endJob"></sage-end-job>
    <?php if(Auth::user()): ?>
        <!-- Chat Placeholder -->
    <?php endif; ?>

    <?php if(\Request::get('intent') != 'clientReference'): ?>
        <sage-cta delay="30000"></sage-cta>
    <?php endif; ?>

<?php if(\Request::get('intent') == 'clientReference' && !\Auth::user() && empty($job->client_id)): ?>
    <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceModal" v-cloak>

        <p>You were invited to provide a reference for this job by <?php if($job->agency): ?><?php echo e($job->agency->name); ?> <?php else: ?> <?php echo e($job->freelancer->name); ?> <?php endif; ?> </p>
        <p>If you already have an account, please sign in. If you do not have an account please create a new account to continue, it will only take a second. </p>
        <hr class="green-hr"/>
        <div class="text-center">
            <md-button href="/login?r=<?php echo e(url('/job/' . $job->id . "?intent=clientReference")); ?>" class="md-primary md-raised md-adj-width">
                Sign In
            </md-button>
            <md-button href="/register?r=<?php echo e(url('/job/' . $job->id . "?intent=clientReference")); ?>" class="md-primary md-raised md-adj-width">
                Create An Account
            </md-button>
        </div>

    </sage-modal>
<?php endif; ?>

<?php if(\Request::get('intent') == 'clientReference' && \Auth::user() && empty($job->client_id)): ?>
    <sage-modal :delay="300" title="Provide a Reference" :show-logo="true" logo-size="xs" name="provideReferenceFullModal" v-cloak>

        <p><?php if($job->agency): ?> <?php echo e($job->agency->name); ?> <?php else: ?> <?php echo e($job->freelancer->name); ?> <?php endif; ?>  invited you to provide a reference for this job.</p>
        <p>Please write a short note about your experience with this <?php if($job->agency): ?> agency. <?php else: ?> freelancer. <?php endif; ?> </p><p>Keep in mind your feedback will be displayed on their public profile.</p>

        <div >
            <sage-job-feedback></sage-job-feedback>
        </div>

    </sage-modal>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
		$(document).ready(function () {
			$(".sageImage").fancybox();
		});
    </script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
(adsbygoogle = window.adsbygoogle || []).push({});
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>