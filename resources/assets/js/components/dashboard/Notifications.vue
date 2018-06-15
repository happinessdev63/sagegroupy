
<template>
    <div>
        <div class="bg-white border-radius-5 margin-bottom-20 md-whiteframe-3dp">
            <h4 class="no-margin-bottom pull-left margin-left-20 margin-top-15 tour-notifications">
                <md-icon class="font-size-20 margin-right-10">
                    chat
                </md-icon>
                Notifications
            </h4>

            <md-button v-if="isFiltered" class="md-primary pull-right" @click="clearFilters">
                <md-icon>clear</md-icon>
                Clear
            </md-button>

            <md-menu md-size="4" class="pull-right">
                <md-button class="md-primary" md-menu-trigger>
                    <md-icon>filter_list</md-icon>
                    Filter
                </md-button>

                <md-menu-content>
                    <md-menu-item @click="onFilter('filter-all');">
                        <span>Show All</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-archived')">
                        <span>Archived</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-unread')">
                        <span>Unread</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-read')">
                        <span>Read</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-sent')">
                        <span>Sent Messages</span>
                    </md-menu-item>

                </md-menu-content>
            </md-menu>

            <!--
            <md-button class="md-primary pull-right" @click.native="markAllRead()">
                <md-icon>done</md-icon>
                Mark All Read
            </md-button>
            -->

            <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
                <span class="font-size-22 font-weight-100 text-primary">{{ totalUnreadNotifications }}</span><br/>
                <span class="font-size-16 font-weight-100">Unread</span>
            </div>

            <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
                <span class="font-size-22 font-weight-100 text-danger">{{ totalReadNotifications }}</span><br/>
                <span class="font-size-16 font-weight-100">Read</span>
            </div>

            <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
                <span class="font-size-22 font-weight-100 text-warning">{{ totalNotifications }}</span><br/>
                <span class="font-size-16 font-weight-100">Total</span>
            </div>

            <div class="clearfix"></div>

            <hr class="no-margin-bottom green-hr"/>

            <div class="dash-card">

                <!-- Cloaked Place Holder Loading Content -->
                <div v-if="state.loading" class="margin-top-20 text-center">
                    <div class="padding-30 text-center">
                        <div class="animated-background">
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                            <div class="background-masker content-row"></div>
                            <div class="background-masker content-spacer"></div>
                        </div>
                    </div>
                </div>
                <!-- End place holders -->

                <div v-if="totalNotifications == 0 && !state.loading" class="padding-10">
                    <div class="text-center well">
                        <h5 class="font-size-16">
                            <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                            <br/>
                            No new notifications.
                        </h5>
                    </div>
                </div>


                <md-table v-if="!state.loading && shared.notifications.length > 0">
                    <md-table-header>
                        <md-table-row>
                            <md-table-head>Type</md-table-head>
                            <md-table-head>Message</md-table-head>
                            <md-table-head style="min-width: 150px;">Status</md-table-head>
                            <md-table-head>Options</md-table-head>
                        </md-table-row>
                    </md-table-header>

                    <md-table-body v-for="(group,groupIndex) in shared.notifications" :key="groupIndex">
                        <md-table-row v-for="(notification,index) in group" v-if="index == 0 || expandedGroup == index || notification.label != 'User'" :key="index">
                            <md-table-cell>
                                <div>
                                    <md-icon class="font-size-30 text-muted margin-bottom-10">
                                        {{ notification.icon}}
                                    </md-icon>
                                    <br/>
                                    <span class="label label-default no-margin-xs">{{ notification.label }}</span>
                                </div>
                            </md-table-cell>
                            <md-table-cell>
                                <div class="">
                                    <a href="#" @click.prevent="openNotification(notification)" class="link-primary font-weight-600 font-size-14">{{ notification.nice_title }}</a>
                                    <p>{{ notification.nice_message }}</p>
                                    <a href="#" @click.prevent="openNotification(notification)"
                                       class="link-primary margin-top-10 font-size-12"
                                       v-if="index == 0 && group.length > 1 && notification.label == 'User'">
                                        View All {{ group.length }} Messages From This User
                                    </a>
                                    <a href="#" @click.prevent="openNotification(notification)"
                                       class="link-primary margin-top-10 font-size-12"
                                       v-if="notification.type == 'recommendation'">
                                        View & Edit Your Recommendations
                                    </a>
                                </div>
                            </md-table-cell>
                            <md-table-cell>
                                <div>
                                    <span class="label label-primary no-margin-xs">{{ notification.status|capitalize }}</span>
                                    <br/>
                                    <span class="text-muted">{{ notification.nice_date }}</span>
                                </div>
                            </md-table-cell>
                            <md-table-cell>
                                <md-menu md-size="4" md-direction="top left">
                                    <md-button class="md-icon-button md-list-action tour-notification-menu" md-menu-trigger>
                                        <md-icon clas="md-primary">more_vert</md-icon>
                                    </md-button>

                                    <md-menu-content>
                                        <md-menu-item @click="openChat(notification)" v-if="notification.type == 'user-message'">
                                            <md-icon clas="md-primary">chat_bubble</md-icon>
                                            <span>Reply</span>
                                        </md-menu-item>

                                        <md-menu-item @click="openChat(notification)"
                                                      v-if="notification.type == 'job-invite' && notification.job && notification.job.client_id != notification.owner_id">
                                            <md-icon clas="md-primary">chat_bubble</md-icon>
                                            <span>Message Client</span>
                                        </md-menu-item>

                                        <md-menu-item @click="updateStatus(notification,'read')">
                                            <md-icon clas="md-primary">done</md-icon>
                                            <span>Mark Read</span>
                                        </md-menu-item>

                                        <md-menu-item @click="updateStatus(notification,'archived')">
                                            <md-icon clas="md-primary">delete</md-icon>
                                            <span>Archive</span>
                                        </md-menu-item>

                                        <md-menu-item @click="sendInvite(notification)" v-if="notification.type == 'agency-invite-request' && agency == notification.agency_id">
                                            <md-icon clas="md-primary">event_available</md-icon>
                                            <span>Send Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="acceptInvite(notification)" v-if="notification.type == 'agency-invite' && owner == notification.to_user_id">
                                            <md-icon clas="md-primary">event_available</md-icon>
                                            <span>Accept Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="rejectInvite(notification)" v-if="notification.type == 'agency-invite' && owner == notification.to_user_id">
                                            <md-icon clas="md-primary">event_busy</md-icon>
                                            <span>Reject Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="acceptJobInvite(notification)"
                                                      v-if="notification.type == 'job-invite' && owner == notification.to_user_id && notification.job && notification.job.client_id != notification.owner_id">
                                            <md-icon clas="md-primary">event_available</md-icon>
                                            <span>Accept Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="rejectJobInvite(notification)"
                                                      v-if="notification.type == 'job-invite' && owner == notification.to_user_id && notification.job && notification.job.client_id != notification.owner_id">
                                            <md-icon clas="md-primary">event_busy</md-icon>
                                            <span>Reject Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="revokeInvite(notification)" v-if="notification.type == 'agency-invite' && agency == notification.agency_id">
                                            <md-icon clas="md-primary">event_busy</md-icon>
                                            <span>Revoke Invite</span>
                                        </md-menu-item>

                                        <md-menu-item @click="confirmDeleteNotification(notification.id)">
                                            <md-icon clas="md-primary">delete</md-icon>
                                            <span>Delete</span>
                                        </md-menu-item>

                                    </md-menu-content>
                                </md-menu>
                            </md-table-cell>
                        </md-table-row>
                    </md-table-body>

                </md-table>

            </div>

            <div class="border-radius-5">
                &nbsp;
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
        <div class="dark-scroll" :class="getNotifyClass" id="notify-sidebar">
                <div v-if="shared.notification" id='notifyModal' tabindex="-1" role="dialog">
                    <div>
                        <div>
                            <div class="bg-dark padding-10 margin-bottom-20">
                                <md-button class="pull-right m-t-n-5" @click="closeNotify()">
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
                                        <img :src="shared.notification.sender.avatar" alt="People">
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
                </div>

            </div>
    </div>

</template>
<script type="text/babel">
	export default {
		mounted() {
			console.log(' Notifications component ready.')


			this.options.agency = this.agency;
            /* Only set owner when agency is not set to prevent fetching user and agency notifications
             for overlapping ID's.
             */
			if (!this.options.agency) {
				this.options.owner = this.owner;
			}
			// this.refreshNotifications();
			var vm = this;
			// this.recurringRefresh = setInterval(function () {
			// 	//vm.refreshNotifications(true);
			// }, 7000);
		},
		created() {

		},
		props: ['owner', 'agency'],
		data() {
			return {
				user: window.Laravel.user,
				shared: window.sageSource,
				totalNotifications: 0,
				recurringRefresh: null,
				expandedGroup: 0,
				errors: {},
				options: {
					'owner': null,
					agency: null,
					'pager': {
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
			showCompletedNotifications: function () {
				return this.state.showCompletedNotifications || this.totalNotifications == this.totalCompleteNotifications;
			},
			totalReadNotifications: function () {
				var filteredNotifications = [];
				var countNotifications = 0;
				this.shared.notifications.forEach(function (notifications) {
					filteredNotifications = notifications.filter(function (notification) {
						return notification.status != 'unread';
					});
					if (filteredNotifications.length == notifications.length) {
						countNotifications++;
					}
				});
				return countNotifications;
			},
			isFiltered: function () {
				return this.options.search || this.options.filter;
			},
			totalUnreadNotifications: function () {
				var filteredNotifications = [];
				var countNotifications = 0;
				this.shared.notifications.forEach(function(notifications) {
					filteredNotifications = notifications.filter(function (notification) {
						return notification.status == 'unread';
					});

					if (filteredNotifications.length == notifications.length || filteredNotifications.length > 0) {
						countNotifications++;
                    }
                });
				return countNotifications;
			}
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
			closeNotify() {
				this.state.open = false;
			},
			onFilter(filter) {
				this.options.filter = filter;
				this.refreshNotifications();
			},
			clearFilters() {
				this.options.search = '';
				this.options.filter = '';
				this.refreshNotifications();
			},
			openNotification(notification) {
				this.shared.notification = notification;

				if (notification.type == 'user-message') {
					this.openChat(notification);
					return;
                }

				this.state.open = true;
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
					this.refreshNotifications();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			showAllNotifications() {
				this.options.pager.size = 1000;
				this.refreshNotifications();
			},
			refreshNotifications(noLoader)
			{

				if (!noLoader) {
					this.state.loading = true;
				}

				this.$http.get('/apiv1/notifications', {params: this.options}).then((response) => {
					if (this.options.paginate) {
						this.shared.notifications = response.body.data;
						this.totalNotifications = response.body.total;
					} else {
						this.shared.notifications = response.body;
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
