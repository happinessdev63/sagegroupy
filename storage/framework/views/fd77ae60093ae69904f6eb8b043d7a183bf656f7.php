<?php $__env->startSection('title'); ?>
    <title>View <?php echo e($user->name); ?> Profile on SageGroupy, The Freelancer's Friend </title>
<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('header-scripts'); ?>
    <script>
		window.Laravel.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.user = <?php echo json_encode( Auth::user() ?: [] ); ?>;
		window.sageSource.profile = <?php echo json_encode( $user ); ?>;
		window.sageSource.curPage = 'viewProfile';
    </script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
(adsbygoogle = window.adsbygoogle || []).push({});
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extras'); ?>
    <?php if(Auth::user() && Auth::user()->id): ?>
        <!-- Chat Placeholder -->
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-3">
            <div class="md-whiteframe-3dp  bg-white padding-10">
                <div class="text-center margin-top-10" v-cloak>
                    <md-avatar class="md-x-large">
                        <img src="<?php echo e($user->avatar); ?>" alt="<?php echo e($user->name); ?>">
                    </md-avatar>

                    <h3 class=" "><?php echo e($user->name); ?></h3>
                    <h5><?php echo e($user->tagline); ?></h5>
                    <p><?php echo e($user->city); ?>, <?php echo e($user->country); ?></p>

                    <?php if($user->isFreelancer): ?>
                        <div>
                            <?php $__currentLoopData = $user->skills->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5"><?php echo e($skill->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if(count($user->skills) == 0): ?>
                                <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">No Skills Added Yet</span>
                            <?php else: ?>
                                <a @click="emitEvent('showUserSkills')" class="label label-default margin-right-10 margin-bottom-10 display-inline-flex padding-5">View More...</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="padding-10 text-center">
                <?php if(Auth::user() &&  Auth::user()->id != $user->id): ?>
                    <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click.native='contactUser(shared.profile)'>Contact <?php echo e($user->firstName); ?></md-button>
                    <?php if(\Auth::user() && Auth::user()->isAdmin): ?>
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" target="_blank" href="/profile/postPdf/<?php echo e($user->id); ?>">Download Profile</md-button>
                    <?php endif; ?>
                    <?php if(\Auth::user() && Auth::user()->isClient && $user->isFreelancer): ?>
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="emitEvent('openInvite', <?php echo e($user); ?>)">Invite <?php echo e($user->firstName); ?> to a Job</md-button>
                    <?php endif; ?>
                    <?php if($user->isFreelancer && Auth::user() && Auth::user()->isFreelancer ): ?>
                        <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="emitEvent('openAgencyInvite', <?php echo e($user); ?>)">Invite <?php echo e($user->firstName); ?> to Your Agency</md-button>
                    <?php endif; ?>
                <?php else: ?>
                    <sage-share-profile-url></sage-share-profile-url>
                <?php endif; ?>
            </div>

            <div class="text-center margin-bottom-10">
                <!-- Profiles Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6500659003306107"
                     data-ad-slot="1323909011"
                     data-ad-format="auto"></ins>
            </div>
        </div>

        <div class="col-md-9">
            <div class="md-whiteframe-3dp bg-white padding-10">
                <div class="clearfix"></div>

                <div class="col-lg-12 margin-bottom-15">
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
                    <div id="profileDetails" v-cloak>

                        <h4 class="margin-top-10 margin-bottom-5">Profile Bio for <?php echo e($user->name); ?></h4>
                        <hr class="green-hr margin-top-5"/>
                        <?php if($user->bio): ?>
                            <?php echo nl2br(strip_tags($user->bio, "<br><p><b><em><i><strong><ul><li>")); ?>

                        <?php else: ?>
                            <div class="text-center well">
                                <h4>
                                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                                </h4>
                                <p>Oops! It looks like <?php echo e($user->firstName); ?> hasn't filled in all of their profile details yet. That doesn't mean they aren't awesome though. You can still contact this user directly to request additional details.</p>
                                <md-button class="md-primary md-raised" @click='contactUser(shared.profile)'>
                                                                              Contact <?php echo e($user->firstName); ?>

                                </md-button>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-lg-12 margin-top-10">
                    <h4 class="margin-bottom-10">Job References & Job History</h4>
                    <hr class="green-hr margin-top-5"/>
                    <sage-profile-references></sage-profile-references>
                </div>

                <div class="clearfix"></div>

                <?php if($user->isFreelancer && $user->generalReferences->count() > 0): ?>
                <div class="col-lg-12 margin-top-10">
                    <h4 class="margin-bottom-10">Additional References</h4>
                    <hr class="green-hr margin-top-5"/>
                    <md-list class="custom-list md-triple-line" v-cloak>
                        <?php $__currentLoopData = $user->generalReferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <md-list-item>
                            <?php if(count($reference->files) > 0): ?>
                            <div class="margin-right-20">
                                <img src="<?php echo e($reference->files[0]['url']); ?>" class="img-thumbnail sageImage" width="80" height="80" alt="Primary Image">
                            </div>
                            <?php else: ?>

                            <div class="md-icon-auto-height" style="min-width: 100px;">
                                <md-icon class="font-size-40">
                                    class
                                </md-icon>
                            </div>

                            <?php endif; ?>

                            <div class="md-list-text-container  margin-top-10">
                                <a class="font-weight-500 link-primary" href="/generalReference/<?php echo e($reference->id); ?>"><?php echo e($reference->title); ?></a>
                                <span>Added <?php echo e($reference->start_date); ?> </span>

                                <?php if($reference->has_client && !empty($reference->review)): ?>
                                    <div>
                                        <div class="font-weight-200 margin-left-10 margin-top-5">
                                            <md-avatar class="pull-left margin-right-20">
                                                <img src="<?php echo e($reference->client->avatar); ?>" alt="People">
                                            </md-avatar>
                                            <span class="font-weight-500">Reference from <a href="/profile/<?php echo e($reference->client->id); ?>" target="_blank" class="link-primary margin-top-20"><?php echo e($reference->client->name); ?></a></span>
                                            <br/>
                                            <p class="margin-bottom-5">
                                                <?php echo $reference->review; ?>

                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php else: ?>
                                    <div>
                                        <?php echo $reference->description; ?>

                                        <?php if(!empty($reference->url) && !empty($reference->url_description)): ?>
                                        <div class="margin-top-10">
                                            <a class="link-primary" href="<?php echo e($reference->url); ?>" target="_blank" rel="nofollow">Visit Related Link (<?php echo e($reference->url_description); ?>)</a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <md-divider class="md-inset-110"></md-divider>
                        </md-list-item>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </md-list>
                </div>
                <?php endif; ?>

                <div class="clearfix"></div>

                <div class="col-lg-12 col-sm-12">
                    <h4>Agencies</h4>
                    <hr class="green-hr margin-top-5"/>
                    <sage-profile-agencies></sage-profile-agencies>
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
                </div>

                <div class="clearfix"></div>

                <hr class="margin-top-10"/>
                <?php if($user->isFreelancer): ?>
                    <sage-links></sage-links>
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
                <?php endif; ?>
                <div class="clearfix"></div>

                <div class="col-lg-12">
                <h4 class="margin-bottom-10 margin-top-20">Portfolio & Media</h4>
                <hr class="green-hr margin-top-5"/>
                <?php if(count($user->public_media) > 0): ?>
                    <?php $__currentLoopData = $user->public_media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <!-- Only display public media files -->
                        <?php if(!$media->pivot->public): ?>
                            <?php continue; ?>
                        <?php endif; ?>

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
                            This user has not added any portfolio or media items yet.
                        </h5>
                    </div>
                <?php endif; ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <sage-skills-modal :user="shared.profile"></sage-skills-modal>
    <sage-cta delay="3300"></sage-cta>
    <sage-agency-invite></sage-agency-invite>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
		$(document).ready(function () {
			$(".sageImage").fancybox();
		});
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>