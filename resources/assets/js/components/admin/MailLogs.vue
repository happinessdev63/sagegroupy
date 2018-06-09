<template>
<div>
    <md-table-card>
        <md-toolbar>
            <h1 class="md-title">Mail Logs</h1>
            <!--
            <md-menu md-size="4">
                <md-button class="md-icon-button" md-menu-trigger>
                    <md-icon>filter_list</md-icon>
                </md-button>

                <md-menu-content>
                    <md-menu-item @selected="onFilter" >
                        <span id="filter-members">More Than One Member</span>
                    </md-menu-item>

                    <md-menu-item @selected="onFilter" >
                        <span id="filter-no-members">No Members Yet (Owner Only)</span>
                    </md-menu-item>
                    
                    <md-menu-item>
                        <span>More Later...</span>
                    </md-menu-item>

                </md-menu-content>
            </md-menu>
            -->

            <md-button class="md-icon-button" @click="refreshTable()">
                <md-icon>cached</md-icon>
            </md-button>

            <md-button v-if="isFiltered" class="md-icon-button" @click="clearFilters">
                <md-icon>clear</md-icon>
            </md-button>

            <div v-if="state.searchSelected" class="col-xs-12">
                <md-input-container md-inline>
                    <label>Search Mail Logs</label>
                    <md-input v-model="options.search" @change="onSearch"></md-input>
                    <md-button  class="md-input-button" @click="state.searchSelected = !state.searchSelected">
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

        <md-table v-else  :md-sort="options.sort.name" :md-sort-type="options.sort.type" @sort="onSort" @select="onSelected">
            <md-table-header>
                <md-table-row>
                    <md-table-head md-sort-by="type">Type</md-table-head>
                    <md-table-head md-sort-by="receiver">Receiver</md-table-head>
                    <md-table-head>Agency</md-table-head>
                    <md-table-head>Job</md-table-head>
                    <md-table-head md-sort-by="status">Status</md-table-head>
                    <md-table-head md-sort-by="created_at">Created At</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="(log, rowIndex) in logs" :key="rowIndex" :md-item="log">
                    <md-table-cell>
                        <span>
                            {{ log.nice_type }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <a v-if="log.receiver":href="profileLink(log.receiver)" class="link-primary" target="_blank">{{ userFormat(log.receiver) }}</a>
                        <span v-else>
                            N/A
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <a v-if="log.agency" :href="agencyLink(log.agency)" class="link-primary" target="_blank">{{ log.agency.name|limitTo(40) }}</a>
                        <span v-else>
                            N/A
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <a v-if="log.job" :href="jobLink(log.job)" class="link-primary" target="_blank">{{ log.job.title|limitTo(40) }}</a>
                        <span v-else>
                            N/A
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span  class="label label-primary pading-10-5">{{ log.status|capitalize }}</span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ log.created_at }}
                        </span>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>
        </md-table >
        <md-table-pagination
                :md-size="options.pager.size"
                :md-total="totalLogs"
                :md-page="options.pager.page"
                md-label="Mail Logs"
                md-separator="of"
                :md-page-options="[20, 50, 100]"
                @pagination="onPagination"></md-table-pagination>

        <div class="margin-top-40">&nbsp;</div>
    </md-table-card>

</div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Admin mailLogs component ready.')
            this.refreshTable();
        },
        data: () => ({
            state: {
                searchSelected : false,
                loading: false,
                selectedAgency : null,
            },
            columns: ['name','owner','location','members','status','created_at'],
            defaultColumns: ['name','created_at'],
            labelColumns: ['status','members'],
            userColumns: ['owner'],
            options: {
                pager: {
                    page: 1,
                    size: 20
                },
                sort : {
                    name: 'created_at',
                    type: 'desc'
                },
                search: '',
                filter: null
            },
            totalMailLogs: 5000,
            user: window.Laravel.user,
            shared: window.sageSource,
            searchTerm: null,
            logs: [],
            notifyPosition: 'right',
            allowHtmlNotifications: true,
            queueNotifications: true,
        }),
        computed: {
            isFiltered : function() {
                return this.options.search || this.options.filter;
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
            userFormat(user) {
                if (user) {
                    return user.name;
                }

                return "N/A";
            },
            agencyLink: function (agency) {
                return "/agency/" + agency.id;
            },
			jobLink: function (job) {
				return "/job/" + job.id;
			},
            profileLink: function (profile) {
                return "/profile/" + profile.id;
            },
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
                this.$http.get('/apiv1/mailLogs', {params : this.options}).then((response) => {

                    this.logs = response.body.data;
                    this.options.pager.page = response.body['current_page'];
                    this.totalMailLogs = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    console.log("Error loading mailLogs.");
                    console.log(response);
                });
            },
            showNotification(message) {
                this.$root.showNotification(message);
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