
<template>
    <div>
        <div class="dark-scroll" :class="getNotifyClass" id="notify-sidebar">
                <div class="text-muted sidebar-title padding-10">
                    <md-button class="pull-right m-t-n-5" @click="state.open = false">
                        <md-icon>close</md-icon>
                    </md-button>
                    <h4 class="modal-title text-white">
                        <md-icon>notifications</md-icon>
                        Notifications
                    </h4>
                </div>

                <div v-if="shared.notification && shared.notification.id" id='notifyModal' tabindex="-1" role="dialog">
                    <div>
                        <div>
                            <div class=" text-muted padding-10 margin-bottom-20">
                                <md-button class="pull-right m-t-n-5" @click="shared.notification = shared.defaultNotification">
                                    <md-icon>close</md-icon>
                                </md-button>

                                <span class="modal-title font-size-14">
                                <md-icon class="margin-bottom-30 margin-right-10 pull-left">{{ shared.notification.icon}}</md-icon>
                                {{ shared.notification.title}}
                            </span>

                            </div>
                            <div id="messageHolder" class="well padding-10 margin-left-10 margin-right-10 margin-bottom-20 notify-messages font-size-16">
                                <!-- User profile -->
                                <div class="margin-bottom-10"
                                     v-if="shared.notification.from_user_id != shared.user.id && shared.notification.sender_type == 'user' && shared.notification.sender">
                                    <md-avatar class="md-large pull-left margin-right-20">
                                        <img :src="shared.notification.sender.avatar" alt="User">
                                    </md-avatar>
                                    <div class="text-left margin-bottom-20">
                                        <label class="label label-primary padding-5 pull-right">{{ shared.notification.sender.nice_role }}</label>
                                        <a class="margin-bottom-5 no-margin-top font-weight-600 link-primary" :href="`/profile/${shared.notification.sender.id}`">
                                            {{ shared.notification.sender.name }}
                                        </a> <br/>
                                    </div>
                                </div>

                                <div class="margin-bottom-10"
                                     v-if="shared.notification.to_user_id != shared.user.id && shared.notification.sender_type == 'user' && shared.notification.receiver">
                                    <md-avatar class="md-large pull-left margin-right-20">
                                        <img :src="shared.notification.receiver.avatar" alt="People">
                                    </md-avatar>
                                    <div class="text-left margin-bottom-20">
                                        <label class="label label-primary padding-5 pull-right">{{ shared.notification.receiver.nice_role }}</label>
                                        <a class="margin-bottom-5 no-margin-top font-weight-600 link-primary"
                                           :href="`/profile/${shared.notification.receiver.id}`">
                                            {{ shared.notification.receiver.name }}
                                        </a> <br/>
                                    </div>
                                </div>

                                <!-- Job profile -->
                                <div class="margin-bottom-10" v-if="shared.notification.sender_type == 'job' && shared.notification.job">
                                    <div class="text-left margin-bottom-20">
                                        <md-icon class="pull-left margin-right-20">
                                            {{ shared.notification.icon }}
                                        </md-icon>
                                        <label class="label label-primary padding-5 pull-right">Job</label>
                                        <a class="margin-bottom-5 no-margin-top font-weight-600 link-primary" :href="`/job/${shared.notification.job.id}`">
                                            {{ shared.notification.job.title }}
                                        </a> <br/>
                                    </div>
                                </div>

                                <!-- Agency profile -->
                                <div class="margin-bottom-10" v-if="shared.notification.sender_type == 'agency' && shared.notification.agency">
                                    <md-avatar class="md-large pull-left margin-right-20">
                                        <img :src="shared.notification.agency.avatar" alt="People">
                                    </md-avatar>
                                    <div class="text-left margin-bottom-20">
                                        <label class="label label-primary padding-5 pull-right">Agency</label>
                                        <a class="margin-bottom-5 no-margin-top font-weight-600 link-primary" :href="`/agency/${shared.notification.agency.id}`">
                                            {{ shared.notification.agency.name }}
                                        </a> <br/>
                                    </div>
                                </div>

                                <hr class="green-hr margin-top-5"/>
                                <div class="clearfix"></div>
                                <p class="padding-5 padding-top-15">{{ shared.notification.message }}</p>
                                <div class="margin-top-1 text-center">
                                    <md-button class="md-primary md-adj-width" @click="openChat(shared.notification)"
                                               v-if="shared.notification.type == 'job-invite' && shared.notification.job && shared.notification.job.client_id != shared.notification.owner_id">
                                        <md-icon clas="md-primary">chat_bubble</md-icon>
                                        <span>Message Client</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="openChat(shared.notification)"
                                               v-if="shared.notification.type == 'user-message'">
                                        <md-icon clas="md-primary">chat_bubble</md-icon>
                                        <span>Message User</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="sendInvite(shared.notification)" v-if="shared.notification.type == 'agency-invite-request' && agency == shared.notification.agency_id">
                                        <md-icon clas="md-primary">event_available</md-icon>
                                        <span>Send Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="acceptInvite(shared.notification)" v-if="shared.notification.type == 'agency-invite' && owner == shared.notification.to_user_id">
                                        <md-icon clas="md-primary">event_available</md-icon>
                                        <span>Accept Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="rejectInvite(shared.notification)" v-if="shared.notification.type == 'agency-invite' && owner == shared.notification.to_user_id">
                                        <md-icon clas="md-primary">event_busy</md-icon>
                                        <span>Reject Invite</span>
                                    </md-button>

                                    <!-- Agency Job Invites -->
                                    <md-button class="md-primary md-adj-width" @click="acceptJobInvite(shared.notification)"
                                               v-if="shared.notification.type == 'job-invite' && shared.notification.agency_id && owner == shared.notification.owner_id">
                                        <md-icon clas="md-primary">event_available</md-icon>
                                        <span>Accept Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="rejectJobInvite(shared.notification)"
                                               v-if="shared.notification.type == 'job-invite' && shared.notification.agency_id && owner == shared.notification.owner_id">
                                        <md-icon clas="md-primary">event_busy</md-icon>
                                        <span>Reject Invite</span>
                                    </md-button>
                                    <!-- End Agency Job Invites -->

                                    <md-button class="md-primary md-adj-width" @click="acceptJobInvite(shared.notification)"
                                               v-if="shared.notification.type == 'job-invite' && owner == shared.notification.to_user_id && shared.notification.job && shared.notification.job.client_id != shared.notification.owner_id">
                                        <md-icon clas="md-primary">event_available</md-icon>
                                        <span>Accept Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="rejectJobInvite(shared.notification)"
                                               v-if="shared.notification.type == 'job-invite' && owner == shared.notification.to_user_id && shared.notification.job && shared.notification.job.client_id != shared.notification.owner_id">
                                        <md-icon clas="md-primary">event_busy</md-icon>
                                        <span>Reject Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" @click="revokeInvite(shared.notification)" v-if="shared.notification.type == 'agency-invite' && agency == shared.notification.agency_id">
                                        <md-icon clas="md-primary">event_busy</md-icon>
                                        <span>Revoke Invite</span>
                                    </md-button>

                                    <md-button class="md-primary md-adj-width" :href="actionLink(shared.notification)">
                                        <md-icon clas="md-primary">info_outline</md-icon>
                                        <span>{{ actionTitle(shared.notification) }}</span>
                                    </md-button>
                                </div>


                            </div>
                            <div class="text-center">
                                <md-button class="md-primary md-raised md-sm"
                                           @click="updateStatus(shared.notification,'read')" v-if="shared.notification.status === 'unread'">Mark Read
                                </md-button>
                                <md-button class="md-primary md-raised md-sm"
                                           @click="updateStatus(shared.notification,'unread')" v-if="shared.notification.status === 'read'">Mark Unread
                                </md-button>
                                <md-button class="md-warn md-raised md-sm"
                                           @click="confirmDeleteNotification(shared.notification.id)">Delete
                                </md-button>
                                <md-button class="md-warn md-raised md-sm"
                                           @click="updateStatus(shared.notification,'archived')">Archive
                                </md-button>
                            </div>
                        </div>
                    </div>
                    <hr class="green-hr no-margin"/>
                </div>
                <div>
                    <div v-for="(group,groupIndex) in shared.sidebarNotifications ">
                        <div
                             v-for="(notification,index) in group" v-if="index == 0 || expandedGroup == index || notification.label != 'User'">
                            <div class="padding-10 cursor-pointer  notification-item" @click.prevent="shared.notification = notification">
                            <span class="pull-right"><md-icon class="text-white">pageview</md-icon></span>
                            <span class="text-white">
                                {{ notification.title }}
                                <span v-if="index == 0 && group.length > 1 && notification.label == 'User'">
                                    ({{ group.length -1 }} more)
                                </span>
                            </span><br/><br/>
                                <span class="label label-primary font-size-10 margin-top-5">{{ notification.nice_date }}</span>

                            </div>
                            <hr class="green-hr no-margin"/>
                        </div>
                    </div>
                </div>
        </div>
        <md-dialog-confirm
                md-title="Delete this notification?"
                md-content-html="This action can not be reversed and the notification along with its data will be permenantly deleted."
                md-ok-text="Delete Notification"
                md-cancel-text="Cancel"
                @close="deleteNotification"
                ref="delete-notification">
        </md-dialog-confirm>
    </div>
</template>
<script type="text/babel">
	export default {
		mounted() {
			console.log(' Notifications sidebar component ready.');
			this.options.owner = this.user.id;
			this.owner = this.user.id;
			if (this.user.id) {
				this.refreshNotifications();
            }

		},
		created() {

		},
		props: [],
		data() {
			return {
				user: window.Laravel.user,
				shared: window.sageSource,
				totalNotifications: 0,
				recurringRefresh: null,
				expandedGroup: 0,
				errors: {},
				owner: null,
				options: {
					owner: null,
					agency: null,
					pager: {
						page: 1,
						size: 25
					},
					paginate: false,
					status: 'unread',
					group: true,
					search: '',
					filter: null
				},
				state: {
					showCompletedNotifications: false,
					selectedNotification: {},
					loading: true,
					deleteId: null,
					open: false
				}
			}
		},
		computed: {
			getNotifyClass: function () {
				if (this.state.open) {
					return 'open';
				} else {
					return 'closed';
				}
			},
		},
		filters: {
			limitTo: function (string, value) {
				if (!string) {
					return '';
				}

				if (string.length <= value) {
					return string;
				}

				return string.substring(0, value) + '...';
			},
			capitalize: function (text) {
				return text[0].toUpperCase() + text.slice(1)
			}
		},
		methods: {
			openNotify() {
				this.state.open = true;
            },
			closeNotify() {
				this.state.open = false;
			},
			openNotification(notification) {
				this.shared.notification = notification;

				if (notification.type == 'user-message') {
					this.openChat(notification);
					return;
                }
			},
			openChat(notification) {
				this.shared.notification = notification;
				appBus.$emit('openChat', notification);
			},
			/**
			 * Return an action link based on notification type and owner
			 * @param notification
			 */
			actionTitle(notification) {
				if (notification.type == "agency-invite" || notification.type == "agency-invite-request") {
                    /* Link to users profile */
					if (this.agency == notification.agency_id) {
						return "View Profile"
					}

                    /* Link to agency profile */
					if (this.owner == notification.to_user_id) {
						return "View Agency"
					}
				}

				if (notification.type == "recommendation") {
                    /* Link to edit recommendations */
					return "Edit Profile";

				}

				if (notification.type == "user-message") {
                    /* Link to users profile */
					if (this.owner == notification.to_user_id) {
						return "View Profile";
					}

                    /* Link to users profile */
					if (this.owner == notification.from_user_id) {
						return "View Profile";
					}
				}

				if (notification.job && (notification.type == "job-message" || notification.type == "job-reminder" || notification.type == "job-invite")) {
					return "View Job";
				}

			},
			/**
			 * Return an action link based on notification type and owner
			 * @param notification
			 */
			actionLink(notification) {
				if (notification.type == "agency-invite" || notification.type == "agency-invite-request") {
                    /* Link to users profile */
					if (this.agency == notification.agency_id) {
						return "/profile/" + notification.from_user_id;
					}

                    /* Link to agency profile */
					if (this.owner == notification.to_user_id) {
						return "/agency/" + notification.agency_id;
					}
				}

				if (notification.type == "recommendation") {
                    /* Link to edit recommendations */
					return "/profile/edit/" + notification.to_user_id + "/#links";

				}

				if (notification.type == "user-message") {
                    /* Link to users profile */
					if (this.owner == notification.to_user_id) {
						return "/profile/" + notification.from_user_id;
					}

                    /* Link to users profile */
					if (this.owner == notification.from_user_id) {
						return "/profile/" + notification.to_user_id;
					}
				}

				if (notification.job && (notification.type == "job-message" || notification.type == "job-reminder" || notification.type == "job-invite")) {
					return "/job/" + notification.job_id;
				}

			},
			confirmDeleteNotification(id) {
				this.state.deleteId = id;
				this.$refs['delete-notification'].open();
			},
			acceptJobInvite(notification) {
				var apiUrl = '/apiv1/notifications/acceptJobInvite/' + notification.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					setTimeout(() => {
						this.updateStatus(notification, "archived");
						this.refreshNotifications();
					}, 3000);
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			rejectJobInvite(notification) {
				var apiUrl = '/apiv1/notifications/rejectJobInvite/' + notification.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					setTimeout(() => {
						this.updateStatus(notification, "archived");
						this.refreshNotifications();
					}, 3000);
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
				this.updateStatus(notification, "archived");
			},
			acceptInvite(notification) {
				var apiUrl = '/apiv1/notifications/acceptInvite/' + notification.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.updateStatus(notification, "archived");
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			rejectInvite(notification) {
				var apiUrl = '/apiv1/notifications/rejectInvite/' + notification.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.updateStatus(notification, "archived");
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
				this.updateStatus(notification, "archived");
			},
			revokeInvite(notification) {
				var apiUrl = '/apiv1/notifications/revokeInvite/' + notification.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.updateStatus(notification, "archived");
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			sendInvite(notification) {

				var apiUrl = '/apiv1/agencies/invite/' + this.shared.agency.id

				this.$http.post(apiUrl, {'user_id': notification.from_user_id}).then((response) => {
					this.$root.showNotification(response.body.message);
				}, (response) => {
					if (response.body.status == 'error' && response.body.message) {
						this.$root.showNotification(response.body.message);
					}
				});
			},
			updateStatus(notification, status) {
				notification.status = status;
				var apiUrl = '/apiv1/notifications/status/' + notification.id + '/' + status;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			markAllRead() {
				var apiUrl = '/apiv1/notifications/markAllRead/';
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			deleteNotification(confirmation) {

				if (confirmation !== 'ok') {
					return;
				}

				var apiUrl = '/apiv1/notifications/delete/' + this.state.deleteId;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.deleteId = null;
					this.shared.notification = this.shared.defaultNotification;
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			refreshNotifications(noLoader)
			{

				if (!noLoader) {
					this.state.loading = true;
				}

				this.$http.get('/apiv1/notifications', {params: this.options}).then((response) => {
					if (this.options.paginate) {
						this.shared.sidebarNotifications = response.body.data;
						this.totalNotifications = response.body.total;
					} else {
						this.shared.sidebarNotifications = response.body;
						this.totalNotifications = this.shared.notifications.length;
					}

					this.state.loading = false;

				}, (response) => {
					this.$root.showNotification(response.body.message);
					console.log("Error loading notifications");
					console.log(response);
				});
			}
		}
	}
</script>
