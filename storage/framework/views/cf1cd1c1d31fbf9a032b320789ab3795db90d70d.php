<!DOCTYPE html>
<?php $__env->startSection('html'); ?>
    <html lang="en">
    <?php echo $__env->yieldSection(); ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=780">

    <?php $__env->startSection('meta'); ?>
        <!-- META -->

        <?php echo $__env->yieldSection(); ?>

        <?php $__env->startSection('title'); ?>
            <title>SageGroupy</title>
        <?php echo $__env->yieldSection(); ?>

    <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <link href="/js/plugins/jquery.fancybox.min.css" rel="stylesheet" type="text/css">
        <link href="/js/plugins/product-tour.min.css" rel="stylesheet" type="text/css">
        <link href="/css/vue-material.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <script>
  				window.Laravel = <?php echo json_encode( [
                'csrfToken'    => csrf_token(),
                'apiToken'     => Auth::user()->api_token ?? null,
                '_token'       => csrf_token(),
                'user'         => \Auth::check() ? Auth::user() : [],
                'userShareUrl' => \Auth::check() ? Auth::user()->publicProfileUrl : 'Not Available',
          ] ); ?>;

          /* Data objects for the app */
          window.sageSource = {
            jobs: [],
                      references: [],
            user: {
              agencies: [],
              owned_agencies: [],
              client_jobs: [],
              freelancer_jobs: [],
              skills: [],
              references: [],
              recommendations: [],
              recommended_users: [],
              files: [],
              links: [],
            },
            profile: {
              city: "",
              agencies: [],
              owned_agencies: [],
              client_jobs: [],
              freelancer_jobs: [],
              skills: [],
              references: [],
              recommendations: [],
              recommended_users: [],
              links: [],
              files: []
            },
            file: [],
            link: {
              files: [],
            },
            defaultLink: {
              files: [],
            },
            errors: [],
            settings: {},
            skills: [],
            skill: {},
            defaultSkill: {
              name: '',
              level: 'Entry',
              rate: null,
              users_count: 0,
              avg_rates: [
                {
                  "level": "Entry",
                  "rate": null
                },
                {
                  "level": "Junior",
                  "rate": null
                },
                {
                  "level": "Intermediate",
                  "rate": null
                },
                {
                  "level": "Senior",
                  "rate": null
                },
                {
                  "level": "Expert",
                  "rate": null
                }
              ]
            },
            agencies: [],
            freelancers: [],
            agency: {
              name: "",
              description: "",
              company_description: "",
              location: "",
              city: "",
              country: "",
              owner: {},
              jobs: {},
              freelancers: {},
              clients: {},
              references: {},
              files: [],
              links: [],
              avatar: '/img/avatar.jpg'
            },
            job: {
              freelancer: {},
              client: {},
              agency: {}
            },
            notifications: [],
            sidebarNotifications: [],
            chatMessages: [],
            unreadNotifications: 0,
            notification: {
              sender: {},
              receiver: {},
              agency: {},
              fromAgency: {},
              job: {}
            },
            defaultNotification: {
              sender: {},
              receiver: {},
              agency: {},
              fromAgency: {},
              job: {}
            },
            page: '',
            state: {
              show_icon_end_job: false,
                          menu_open: true,
                          window_width: 0,
                          window_height: 0,
                          chat_loaded: false
            },
            ratings: [
              {
                title: '5 Stars',
                value: 5
              },
              {
                title: '4 Stars',
                value: 4
              },
              {
                title: '3 Stars',
                value: 3
              },
              {
                title: '2 Stars',
                value: 2
              },
              {
                title: '1 Star',
                value: 1
              }
            ],
              curPage: 'dashboard'
          };
         </script>

         <script>
            window.Laravel.profile = <?php echo json_encode( $user ); ?>;
            window.sageSource.user = <?php echo json_encode( Auth::user() ?: [] ); ?>;
            window.sageSource.profile = <?php echo json_encode( $user ); ?>;
            window.sageSource.curPage = 'downloadProfile';
         </script>
    </head>
    <body>
        <div id="app" class="md-whiteframe-3dp">

          <div class="row" style="margin-top: 20px; background-color: white; padding: 20px;position:relative;" id="profile">
            <div style="display:inline;" id="logo">
              <div class="col-sm-3" align = "center">
                  <img src="<?php echo e(asset($user->avatar)); ?>" class="img-thumbnail" alt="<?php echo e($user->name); ?>"/>
              </div>
              <div class="col-sm-9">
                <div style="position:relative; float:right; width: 20%; height: 20%; display: inline-block;" id="companyLogo" >
                  <sage-image-cropper-only imageurl = "<?php echo e(asset('/img/logo_xs.png')); ?>"></sage-image-cropper-only>
                  <div style="padding-left: 10%;">
                    <a style="color: rgba(0, 150, 136, 0.95); font-size: 10px; padding-left: 0px; text-decoration: none;" href="/profile/<?php echo e($user->id); ?>">
                      View on SageGroupy
                    </a>
                  </div>
                  <div style="padding-left: 10%;">
                    <md-button @click="$root.emitEvent('editAvatar')" id="changeLogo" class="md-raised md-primary font-size-10 margin-left-5">Change logo</md-button>
                  </div>
                </div>

                <h3 class=" "><?php echo e($user->name); ?></h3>
                <h5><?php echo e($user->tagline); ?></h5>
                <p><?php echo e($user->city); ?>, <?php echo e($user->country); ?></p>

                <?php if($user->isFreelancer): ?>
                    <div>
                        <?php $__currentLoopData = $user->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(count($user->skills) == 0): ?>
                            <span class="label label-primary margin-right-10 margin-bottom-10 display-inline-flex padding-5">No Skills Added Yet</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
              </div>
            </div>

            <div id="aboutPerson" class="col-lg-12 margin-top-10">
                <h4 class="margin-top-10 margin-bottom-5" style="color: #ff5722;"><span class="glyphicon glyphicon-exclamation-sign margin-left-5"></span>About <?php echo e($user->name); ?></h4>
                <hr class="green-hr margin-top-5" style="border-color: #ff5722;"/>
                <?php if($user->bio): ?>
                    <?php echo nl2br(strip_tags($user->bio, "<br><p><b><em><i><strong><ul><li>")); ?>

                <?php endif; ?>

            </div>

            <div id="jobRef" class="col-lg-12 margin-top-10">
                <h4 class="margin-bottom-10" style="color: #2B78FE;"><md-icon>assignment</md-icon>Job References & Job History</h4>
                <hr class="green-hr margin-top-5" style="border-color: #2B78FE;"/>
                <sage-profile-references></sage-profile-references>
            </div>

            <div id="additional" class="col-lg-12 margin-top-10">
                <h4 class="margin-bottom-10" style="color: #e91e63;"><md-icon>class</md-icon>Additional References</h4>
                <hr class="green-hr margin-top-5" style="border-color: #e91e63;"/>
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
                <md-button class="md-raised md-primary pull-right" id="startBtn" @click="makePdf()">Download</md-button>
            </div>
          </div>
      </div>
    </body>

    <script src="/js/app.js"></script>
    <script src="/js/plugins/jquery.fancybox.min.js"></script>
    <script src="/js/plugins/product-tour.min.js"></script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58f7a8bf66ec9acf"></script>
</html>
