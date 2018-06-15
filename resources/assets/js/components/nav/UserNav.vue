<!--

Usage: <sage-nav> </sage-nav>
Properties: current-page [Declares which page should be active in navigation.]

-->

<template>
    <div>
    <div class="full-width no-padding bg-dark border-bottom-1 border-grey-900 fixed top-nav">
        <div class="">
        <md-toolbar v-if="user.id" class="md-transparent">
        <img class="logo" src="/img/logo_xs.png" height="60" width="120"/>
        <md-button class="no-margin no-padding-left" @click="toggleMenu()">
            <md-icon v-if="shared.state.menu_open">chevron_left</md-icon>
            <i class="md-icon material-icons site-menu-icon md-theme-default">menu</i>
            Nav
        </md-button>

        <md-menu md-align-trigger v-if="user.is_admin">
            <md-button md-menu-trigger>
                Admin
                <i class="md-icon material-icons site-menu-icon md-theme-default">keyboard_arrow_down</i>
            </md-button>
            <md-menu-content>
                <md-menu-item><a href="/admin/users" class="text-black">Manage Users</a></md-menu-item>
                <md-menu-item><a href="/admin/jobs" class="text-black">Manage Jobs</a></md-menu-item>
                <md-menu-item><a href="/admin/skills" class="text-black">Manage Skills</a></md-menu-item>
                <md-menu-item><a href="/admin/agencies" class="text-black">Manage Agencies</a></md-menu-item>
                <md-menu-item><a href="/admin/settings" class="text-black">Manage Settings</a></md-menu-item>
                <md-menu-item><a href="/admin/mailLogs" class="text-black">Manage Mail Logs</a></md-menu-item>
                <md-menu-item v-if="user.is_admin" @click="openBulkNotifier">Send Notifications</md-menu-item>
            </md-menu-content>
        </md-menu>

        <div style="flex: 1"></div>
        <md-button class="hidden-xs pull-right" @click="$refs['notificationsSidebar'].openNotify(); ">
            <i class="md-icon material-icons site-menu-icon md-theme-default">notifications</i>
            <label class="label label-primary md-badge"> {{ shared.unreadNotifications }} New</label>
        </md-button>

        <md-button class="hidden-xs pull-right addthis_button">
            <i class="md-icon material-icons site-menu-icon md-theme-default">share</i>
            Share
        </md-button>

        <div class="pull-right">
            <md-button @click="goTo('/profile')">
                <span>{{ user.first_name }}</span>
                <i class="md-icon material-icons site-menu-icon md-theme-default">person</i>
            </md-button>
        </div>
    </md-toolbar>
        <md-toolbar v-if="!user.id" class="md-transparent">
                <img src="/img/icon_xxs.png" height="40" width="40"/>
                <div class="text-center margin-left-10">
                    <h4>The Freelancer's Friend</h4>
                </div>
                <div style="flex: 1"></div>
                <div class="pull-rigtht">
                    <md-button class="md-primary  md-raised" :href="registerUrl">
                        Join Now (it's free!)
                    </md-button>
                </div>
            </md-toolbar>
        </div>
    </div>

        <sage-notifications-sidebar ref="notificationsSidebar"></sage-notifications-sidebar>
    </div>
</template>


<script type="text/babel">
    export default {
        mounted() {
            console.log(' Nav component ready.')
            this.options.owner = this.user.id
            this.originalTitle = document.title;

            var vm = this;
            if (this.user.id) {
				this.loadNotifications();
				this.recurringRefresh = setInterval(function () {
					vm.loadNotifications(true);
				}, 5000);
            }
        },
        props: ['currentPage'],
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                recurringRefresh: null,
                firstLoad: true,
                originalTitle: '',
                originalNotifications: 0,
                options: {
                    'owner': null,
                    'pager': {
                        page: 1,
                        size: 25
                    },
                    paginate: false,
                    countOnly: true,
                    status: 'unread',
                    search: '',
                    filter: null
                },
            }
        },
        computed: {
            registerUrl: function () {
                return "/register?r=" + window.location.href;
            },
        },
        methods: {
			getNotifyClass: function () {
				if (this.state.notificationsOpen) {
					return 'open';
				} else {
					return 'closed';
				}
			},
            loadNotifications() {

                if (!this.user.id) {
                    return;
                }

              this.$http.get('/apiv1/notifications', {params: this.options}).then((response) => {

                  if (this.firstLoad) {
                      this.originalNotifications = parseInt(response.body);
                  }

                  var newMessages = parseInt(response.body) - parseInt(this.shared.unreadNotifications);
                  var totalNewMessages = parseInt(response.body) - this.originalNotifications;

                  if (newMessages > 0 && !this.firstLoad) {
                    document.title = "(" + totalNewMessages + ") " + this.originalTitle;
                      var audio = new Audio('/sounds/new-message.ogg');
                      audio.play();
                  }

                  this.shared.unreadNotifications = response.body;
                  this.firstLoad = false;
                  if (this.shared.unreadNotifications == 0) {
                      document.title = this.originalTitle;
                  }

                });
            },
            goTo(location) {
            	window.location = location;
            },
            openBulkNotifier() {
                appBus.$emit('openBulkNotifications');
            },
			toggleMenu() {
				this.shared.state.menu_open = !this.shared.state.menu_open;
			},
            toggleSidebar(ref) {
                this.$root.toggleSidebar(ref);
            },
        }
    }
</script>
