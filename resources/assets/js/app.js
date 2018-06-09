/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

var VueMaterial = require('vue-material')
Vue.use(VueMaterial);

import PortalVue from 'portal-vue'
Vue.use(PortalVue)

import Trend from 'vuetrend'
Vue.use(Trend);

Vue.material.registerTheme({
    default: {
        primary: {
            color: 'teal',
            hue: '800'
        },
        accent: {
            color: 'blue-grey',
            hue: '100'
        },
        warn: {
            color: 'red',
            hue: 'A200'
        }
    },
    green: {
        primary: 'light-green',
        accent: 'black'
    },
    orange: {
        primary: 'orange',
        accent: 'white'
    },
})
/*Test*/

/* Register UI Components */
Vue.component('ui-textbox', require('./ui/UiTextbox.vue'));
Vue.component('ui-snackbar', require('./ui/UiSnackbar.vue'));
Vue.component('ui-modal', require('./ui/UiModal.vue'));
Vue.component('ui-snackbar-container', require('./ui/UiSnackbarContainer.vue'));
Vue.component('ui-autocomplete', require('./ui/UiAutocomplete.vue'));

/* Register file upload */
Vue.component('sage-dropzone', require('./components/common/Dropzone.vue'));

/* Register Global Components */
Vue.component('sage-login', require('./components/Login.vue'));
Vue.component('sage-register', require('./components/Register.vue'));
Vue.component('sage-password-reset', require('./components/PasswordReset.vue'));
Vue.component('sage-password-reset-confirm', require('./components/PasswordResetConfirm.vue'));
Vue.component('sage-sidenav', require('./components/nav/SideNav.vue'));
Vue.component('sage-nav', require('./components/nav/UserNav.vue'));
Vue.component('sage-login-nav', require('./components/nav/LoginNav.vue'));
Vue.component('sage-rating', require('vue-bulma-rating'));
Vue.component('sage-view-rating', require('./components/common/Rating.vue'));
Vue.component('sage-ads', require('./components/common/Ads.vue'));
Vue.component('sage-editor', require('./components/common/SageEditor.vue'));
Vue.component('sage-share-job', require('./components/jobs/ShareJob.vue'));
Vue.component('sage-cta', require('./components/common/CallToAction.vue'));
Vue.component('sage-modal', require('./components/common/Modal.vue'));
Vue.component('sage-full-modal', require('./components/common/FullModal.vue'));
Vue.component('sage-edit-links', require('./components/common/EditLinks.vue'));
Vue.component('sage-links', require('./components/common/Links.vue'));
Vue.component('sage-invite-freelancer', require('./components/common/InviteFreelancer.vue'));
Vue.component('sage-agency-invite', require('./components/common/InviteToAgency.vue'));
Vue.component('sage-notifications-sidebar', require('./components/common/NotificationsSidebar.vue'));


/* Register Dashboard Components */
Vue.component('sage-notifications', require('./components/dashboard/Notifications.vue'));
Vue.component('sage-chat', require('./components/common/Chat.vue'));
Vue.component('sage-jobs', require('./components/dashboard/Jobs.vue'));
Vue.component('sage-agencies', require('./components/dashboard/Agencies.vue'));

/* Register Profile Components */
Vue.component('sage-share-profile-url', require('./components/profile/ShareProfileUrl.vue'));
Vue.component('sage-edit-profile', require('./components/profile/EditProfile.vue'));
Vue.component('sage-profile-wizard', require('./components/profile/ProfileWizard.vue'));
Vue.component('sage-image-cropper', require('./components/common/vue-image-crop-upload/upload-2.vue'));
Vue.component('sage-profile-agencies', require('./components/profile/Agencies.vue'));
Vue.component('sage-profile-references', require('./components/profile/References.vue'));
Vue.component('sage-profile-edit-references', require('./components/profile/EditReferences.vue'));
Vue.component('sage-profile-edit-skills', require('./components/profile/EditSkills.vue'));
Vue.component('sage-become-client', require('./components/freelancer/BecomeClient.vue'));
Vue.component('sage-become-freelancer', require('./components/client/BecomeFreelancer.vue'));
Vue.component('sage-skills-modal', require('./components/profile/SkillsModal.vue'));
Vue.component('sage-edit-media', require('./components/common/EditMedia.vue'));
Vue.component('sage-freelancer-list', require('./components/freelancer/Freelancers.vue'));

/* Register agency components */
Vue.component('sage-create-agency', require('./components/agencies/CreateAgency.vue'));
Vue.component('sage-agency-jobs', require('./components/agencies/Jobs.vue'));
Vue.component('sage-agencies-list', require('./components/agencies/Agencies.vue'));
Vue.component('sage-agency-links', require('./components/agencies/EditLinks.vue'));
Vue.component('sage-join-agency', require('./components/agencies/JoinAgency.vue'));

/* Register jobs components */
Vue.component('sage-create-job', require('./components/client/CreateJob.vue'));
Vue.component('sage-create-reference-job', require('./components/freelancer/CreateReferenceJob.vue'));
Vue.component('sage-create-general-reference', require('./components/freelancer/CreateGeneralReference.vue'));
Vue.component('sage-create-imported-reference', require('./components/freelancer/CreateImportedReference.vue'));
Vue.component('sage-end-job', require('./components/jobs/EndJob.vue'));
Vue.component('sage-job-feedback', require('./components/jobs/JobFeedback.vue'));
Vue.component('sage-job-nav', require('./components/jobs/JobNav.vue'));

/* Register Admin Components */
Vue.component('sage-admin-users', require('./components/admin/Users.vue'));
Vue.component('sage-admin-jobs', require('./components/admin/Jobs.vue'));
Vue.component('sage-admin-agencies', require('./components/admin/Agencies.vue'));
Vue.component('sage-admin-skills', require('./components/admin/Skills.vue'));
Vue.component('sage-admin-settings', require('./components/admin/Settings.vue'));
Vue.component('sage-admin-mail-logs', require('./components/admin/MailLogs.vue'));
Vue.component('sage-admin-bulk-notify', require('./components/admin/BulkNotification.vue'));


/* Central vue instance for component commonucation */
window.appBus = new Vue();

const app = new Vue({
    el: '#app',
    mounted() {
        var vm = this;
        $(document).ready(function () {
            $('body').removeClass("bg-white").removeClass("md-theme-default").css("background-color", '#f5faf8');
        });


		this.$nextTick(function () {
			window.addEventListener('resize', this.getWindowWidth);
			window.addEventListener('resize', this.getWindowHeight);
		});

		appBus.$on('updateUserMeta', function (event) {
			vm.updateUserMeta(event);
		});

    },
    created() {
		this.getWindowWidth();
		this.getWindowHeight();

        if (this.shared.state.window_width <= 768) {
            this.shared.state.menu_open = false;
        }
    },
    data: {
        shared: window.sageSource,
		feedbackMessage: '',
        user: window.Laravel.user,
		errors: {},
        snackBarMessage: '',
        editorToolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote'],
            [{'list': 'ordered'}, {'list': 'bullet'}],
            [{'indent': '-1'}, {'indent': '+1'}],
            [{'header': [1, 2, 3, 4, 5, 6, false]}],
            [{'align': []}],
            ['clean']
        ],
    },
	watch: {
		'shared.state.menu_open': function () {
			if (this.shared.state.menu_open) {
			    $('body').removeClass('site-menubar-fold').addClass('site-menubar-unfold');
            } else {
				$('body').removeClass('site-menubar-unfold').addClass('site-menubar-fold');
            }
		}
	},
    computed: {
        notificationsLabel() {
            return "Notifications & Activity (" + this.shared.unreadNotifications + ")";
        }
    },
    methods: {
		getWindowWidth(event) {
			this.shared.state.window_width = document.documentElement.clientWidth;
			if (this.shared.state.window_width <= 1024) {
				this.shared.state.menu_open = false;
			}
		},

		getWindowHeight(event) {
			this.shared.state.window_height = document.documentElement.clientHeight;
		},
        hasMedia(job) {
            if (job.media && job.media.length > 0) {
                return true;
            } else {
                return false;
            }
        },
        primaryImageLink(job) {
            if (!this.hasMedia(job)) {
                return false;
            }

            var images = job.media.filter(function (mediaItem) {
                return mediaItem.aggregate_type === 'image';
            });

            if (images.length > 0) {
                var image = images[0];
            } else {
                return false;
            }

            return "/storage/" + image.directory + "/" + image.filename + "." + image.extension;
        },
        jobLink: function (job) {
            return "/job/" + job.id;
        },
        jobEditLink: function (job) {
            if (job.type == "reference") {
                return "/jobs/editReference/" + job.id;
            }

            return "/jobs/edit/" + job.id;
        },
        openDialog(ref) {
            this.$refs[ref].open();
        },
        closeDialog(ref) {
            this.$refs[ref].close();
        },
        toggleSidebar(ref) {
            this.$refs[ref].toggleSideNav();
        },
        labelCount(label,total) {
            return label + " (" + total + ")";
        },
        showNotification(message) {

            this.snackBarMessage = message;
            this.$refs.snackbar.open();
            return;

            this.$refs.mainNotifyContainer.createSnackbar({
                message: message,
                action: '',
                actionColor: 'accent',
                duration: 5000
            });
        },
        onOpen() {
            console.log('Opened');
        },
        onClose(type) {
            console.log('Closed', type);
        },
        emitEvent(eventName,eventOptions) {
            appBus.$emit(eventName, eventOptions);
        },
        contactUser(user) {

            /* Mock a notification from the user we want to send a message to */
            var notification = {
                from_user_id: user.id,
                to_user_id: this.shared.user.id,
                type: 'user-message',
                sender: user
            }

            appBus.$emit('openChat', notification);
        },
        toggleMenu() {
          this.shared.state.menu_open = !this.shared.state.menu_open;
        },
        getNavClass(page) {
            if (this.shared.curPage == page) {
                return 'active';
            }

            return '';
        },
		getMenuClass() {
			if (this.shared.state.menu_open) {
				return 'site-menubar-unfold';
			}

			return 'site-menubar-fold';
		},
        updateUserMeta(fields) {
			this.$http.post('/apiv1/profile/update/' + this.shared.user.id + '/meta', {
				meta: fields
			}).then((response) => {
			    this.shared.user.meta = response.body.meta;
                console.log('meta updated');
			}, (response) => {
				console.log(response);
			});
        },
		sendUserFeedback() {

			this.$http.post('/apiv1/feedback', {'message' : this.feedbackMessage}).then((response) => {

				this.$root.showNotification(response.body.message);
				this.closeDialog('feedbackDialog');

			}, (response) => {
				this.$root.showNotification('Please enter a message, between 5 and 2048 characters.');
			});
		},
		saveGeneralReference() {

			var apiUrl = '/apiv1/generalReference/' + this.shared.reference.id + '/clientFeedback';
			this.$http.post(apiUrl, this.shared.reference).then((response) => {
				this.$root.showNotification(response.body.message);
				setTimeout(() => {
					window.location.reload();
				},4000);
			}, (response) => {
				if (response.body.status == 'error' && response.body.message) {
					this.$root.showNotification(response.body.message);
				} else {
					this.errors = response.body;
				}
			});

		},

    }
});

