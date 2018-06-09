<template>
    <div>
        <md-table-card>

            <ui-snackbar-container
                    ref="usersNotifyContainer"
                    :position="notifyPosition"
                    :queue-snackbars="queueNotifications"
                    :allowHtml="allowHtmlNotifications"
            ></ui-snackbar-container>

            <md-toolbar>
                <h1 class="md-title">Users</h1>
                <md-menu md-size="4">
                    <md-button class="md-icon-button" md-menu-trigger>
                        <md-icon>filter_list</md-icon>
                    </md-button>

                    <md-menu-content>
                        <md-menu-item @selected="onFilter">
                            <span id="filter-clients">Clients</span>
                        </md-menu-item>

                        <md-menu-item @selected="onFilter">
                            <span id="filter-freelancers">Freelancers</span>
                        </md-menu-item>

                        <md-menu-item @selected="onFilter">
                            <span id="filter-clients_freelancers">Clients & Freelancers</span>
                        </md-menu-item>

                        <md-menu-item @selected="onFilter">
                            <span id="filter-admins">Admins</span>
                        </md-menu-item>

                        <md-menu-item>
                            <span>More Later...</span>
                        </md-menu-item>

                    </md-menu-content>
                </md-menu>


                <md-button class="md-icon-button" @click="refreshTable()">
                    <md-icon>cached</md-icon>
                </md-button>

                <md-button v-if="isFiltered" class="md-icon-button" @click="clearFilters">
                    <md-icon>clear</md-icon>
                </md-button>

                <div v-if="state.searchSelected" class="col-xs-12">
                    <md-input-container md-inline>
                        <label>Search Users</label>
                        <md-input v-model="options.search" @change="onSearch"></md-input>
                        <md-button class="md-input-button" @click="state.searchSelected = !state.searchSelected">
                            <md-icon>search</md-icon>
                        </md-button>
                    </md-input-container>
                </div>

                <md-button v-if="!state.searchSelected" class="md-icon-button" @click="state.searchSelected = !state.searchSelected">
                    <md-icon>search</md-icon>
                </md-button>


            </md-toolbar>

            <md-table-alternate-header md-selected-label="selected">
                <md-button class="md-icon-button">
                    <md-icon>delete</md-icon>
                </md-button>
            </md-table-alternate-header>

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

            <md-table v-else :md-sort="options.sort.name" :md-sort-type="options.sort.type" @sort="onSort" @select="onSelected">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="name">Name</md-table-head>
                        <md-table-head md-sort-by="email">Email</md-table-head>
                        <md-table-head md-sort-by="role">Role</md-table-head>
                        <md-table-head md-sort-by="created_at">Created At</md-table-head>
                        <md-table-head >Views</md-table-head>
                        <md-table-head>Options</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(row, rowIndex) in users" :key="rowIndex" :md-item="row">
                        <md-table-cell v-for="(column, columnIndex) in row" :key="columnIndex" v-if="columns.includes(columnIndex)">
                            <span v-if="columnIndex === 'role'" class="label label-primary pading-10-5">{{ column}}</span>
                            <span v-else>{{ column }}</span>
                        </md-table-cell>
                        <md-table-cell>
                            <!--
                            <trend
                                    v-if="users[rowIndex].recent_views.length > 2"
                                    :data="users[rowIndex].recent_views"
                                    :gradient="['#6fa8dc', '#42b983', '#2c3e50']"
                                    auto-draw
                                    smooth>
                            </trend>
                            <span v-else>No Data Yet</span>
                            -->
                            <line-chart v-if="users[rowIndex].recent_views.count > 2"
                                        :data="users[rowIndex].recent_views.stats"
                                        width="300px" height="200px"
                            >
                            </line-chart>
                            <span v-else>No Data Yet</span>

                        </md-table-cell>
                        <md-table-cell>
                            <md-menu md-size="5" md-direction="top left">
                                <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                    <md-icon clas="md-primary">more_vert</md-icon>
                                </md-button>
                                <md-menu-content>
                                    <md-menu-item :href="profileLink(users[rowIndex])">
                                        <md-icon clas="md-primary">account_box</md-icon>
                                        <a class="text-black" :href="profileLink(users[rowIndex])">
                                            <span>View profile</span>
                                        </a>
                                    </md-menu-item>

                                    <md-menu-item @click="loginAsUser(users[rowIndex])">
                                        <md-icon clas="md-primary">assignment_ind</md-icon>
                                        <span>Login as {{ users[rowIndex].first_name }}</span>
                                    </md-menu-item>

                                    <md-menu-item>

                                        <md-icon clas="md-primary">edit</md-icon>
                                        <a class="text-black" :href="profileEditLink(users[rowIndex])">
                                            <span>Edit User</span>
                                        </a>
                                    </md-menu-item>

                                    <md-menu-item @click="$root.contactUser(users[rowIndex])">
                                        <md-icon clas="md-primary">chat_bubble</md-icon>
                                        <span>Send Message</span>
                                    </md-menu-item>

                                    <md-menu-item @click="confirmDeleteUser(users[rowIndex])">
                                        <md-icon clas="md-primary">delete</md-icon>
                                        <span>Delete</span>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>
            <md-table-pagination
                    :md-size="options.pager.size"
                    :md-total="totalUsers"
                    :md-page="options.pager.page"
                    md-label="Users"
                    md-separator="of"
                    :md-page-options="[20, 50, 100]"
                    @pagination="onPagination"></md-table-pagination>

            <div class="margin-top-40">&nbsp;</div>
        </md-table-card>

        <md-dialog-confirm
                md-title="Delete this user?"
                md-content-html="This action can not be reversed and the users data will be permenantly deleted."
                md-ok-text="Delete User"
                md-cancel-text="Cancel"
                @close="deleteUser"
                ref="delete-user">
        </md-dialog-confirm>


    </div>
</template>

<script>
	import Chartkick from 'chartkick'
	import VueChartkick from 'vue-chartkick'
	import Chart from 'chart.js'

	Vue.use(VueChartkick, {Chartkick});

    export default {
        mounted() {
            console.log(' Admin users component ready.')
            this.refreshTable();
        },
        data: () => ({
            state: {
                searchSelected: false,
                loading: false,
                selectedUser: null,
            },
            columns: ['name', 'email', 'role', 'created_at'],
            options: {
                pager: {
                    page: 1,
                    size: 20
                },
                sort: {
                    name: 'created_at',
                    type: 'desc'
                },
                search: '',
                filter: null
            },
            totalUsers: 5000,
            user: window.Laravel.user,
            searchTerm: null,
            users: [],
            notifyPosition: 'right',
            allowHtmlNotifications: true,
            queueNotifications: true,
        }),
        computed: {
            isFiltered: function () {
                return this.options.search || this.options.filter;
            }
        },
        methods: {
            clearFilters() {
                this.options.search = '';
                this.options.filter = '';
                this.state.searchSelected = false;
                this.refreshTable();
            },
            onPagination(pageOptions) {
                this.options.pager = pageOptions;
                this.refreshTable();
            },
            onSort(sortOptions) {
                this.options.sort = sortOptions;
                this.refreshTable();
            },
            onSelected(selectedRows) {
                this.options.selected = selectedRows;
            },
            onFilter(filter) {
                this.options.filter = filter.target.id;
                this.refreshTable();
            },
            onSearch(term) {
                this.options.search = term;
                this.refreshTable();
            },
            refreshTable()
            {
                this.state.loading = true;
                this.options.page = this.options.pager.page;
                this.$http.get('/apiv1/users', {params: this.options}).then((response) => {

                    this.users = response.body.data;
                    this.options.pager.page = response.body['current_page'];
                    this.totalUsers = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    console.log("Error loading users");
                    console.log(response);
                });
            },
            profileLink(user)
            {
                return "/profile/" + user.id;
            },
            profileEditLink(user)
            {
                return "/profile/edit/" + user.id;
            },
            loginAsUser(user) {
                this.$root.showNotification('Logging you in as ' + user.name + "...");
                setTimeout(function () {
                    window.location = "/admin/users/loginAs/" + user.id;
                }, 2000);
            },
            confirmDeleteUser(user) {
                this.state.selectedUser = user;
                this.$refs['delete-user'].open();
            },
            deleteUser(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                if (!this.state.selectedUser || !this.state.selectedUser.id) {
                    this.$root.showNotification('Invalid user selected, please refresh the users and try again.');
                }

                this.$http.post('/apiv1/profile/delete/' + this.state.selectedUser.id, this.state.selectedUser).then((response) => {

                    this.$root.showNotification(response.body.message);
                    this.refreshTable();

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log(response);
                });

                this.selectedUser = null;

            },
            showNotification(message) {
                this.$refs.usersNotifyContainer.createSnackbar({
                    message: message,
                    action: '',
                    actionColor: 'accent',
                    duration: 5000
                });
            },
            openRootDialog(ref) {
                this.$root.openDialog(ref);
            },
            openDialog(ref) {
                this.$refs[ref].open();
            }

        }
    }
</script>