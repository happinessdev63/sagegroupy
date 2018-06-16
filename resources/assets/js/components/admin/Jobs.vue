<template>
<div>
    <md-table-card>

        <ui-snackbar-container
                ref="jobsNotifyContainer"
                :position="notifyPosition"
                :queue-snackbars="queueNotifications"
                :allowHtml="allowHtmlNotifications"
        ></ui-snackbar-container>
        <div class="pull-right">
          <md-toolbar>
              <h1 class="md-title text-black">Jobs (Found {{ totalJobs }})</h1>
              <md-menu md-size="4">
                  <md-button class="md-primary md-icon-button" md-menu-trigger>
                      <md-icon>filter_list</md-icon>
                  </md-button>

                  <md-menu-content class="md-primary">
                      <md-menu-item @selected="onFilter" >
                          <span id="filter-active">In Progress</span>
                      </md-menu-item>

                      <md-menu-item @selected="onFilter" >
                          <span id="filter-completed">Completed</span>
                      </md-menu-item>

                      <md-menu-item @selected="onFilter">
                          <span id="filter-standard">Standard Jobs</span>
                      </md-menu-item>

                      <md-menu-item @selected="onFilter" >
                          <span id="filter-reference">Reference Jobs</span>
                      </md-menu-item>

                      <md-menu-item>
                          <span>More Later...</span>
                      </md-menu-item>

                  </md-menu-content>
              </md-menu>


            <md-button class="md-primary md-icon-button" @click="refreshTable()">
                <md-icon>cached</md-icon>
            </md-button>

            <md-button v-if="isFiltered" class="md-primary md-icon-button" @click="clearFilters">
                <md-icon>clear</md-icon>
            </md-button>

            <div v-if="state.searchSelected" class="col-xs-12">
                <md-input-container md-inline>
                    <label>Search Jobs</label>
                    <md-input v-model="options.search" @change="onSearch"></md-input>
                    <md-button  class="md-primary md-input-button" @click="state.searchSelected = !state.searchSelected">
                        <md-icon>search</md-icon>
                    </md-button>
                </md-input-container>
            </div>

            <md-button v-if="!state.searchSelected" class="md-primary md-icon-button" @click="state.searchSelected = !state.searchSelected">
                  <md-icon>search</md-icon>
            </md-button>

          </md-toolbar>
        </div>

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
                    <md-table-head md-sort-by="name">Title</md-table-head>
                    <md-table-head md-sort-by="client">Client</md-table-head>
                    <md-table-head md-sort-by="freelancer">Freelancer</md-table-head>
                    <md-table-head md-sort-by="agency">Agency</md-table-head>
                    <md-table-head md-sort-by="type">Type</md-table-head>
                    <md-table-head md-sort-by="completed">Status</md-table-head>
                    <md-table-head md-sort-by="created_at">Created At</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="(job, rowIndex) in jobs" :key="rowIndex" :md-item="job">
                    <md-table-cell>
                        <span>
                            {{ job.title|limitTo(24) }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ userFormat(job.client) }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ userFormat(job.freelancer) }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ userFormat(job.agency) }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span class="label label-primary pading-10-5">{{ job.type|capitalize }}</span>
                    </md-table-cell>
                    <md-table-cell>
                        <span v-if="job.completed != '1'" class="label label-primary pading-10-5">{{ job.status|capitalize }}</span>
                        <span v-else class="label label-primary pading-10-5">Completed</span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ job.created_at }}
                        </span>

                        <md-button v-if="user.is_admin" target="blank" :href="jobLink(job)" class="md-icon-button">
                        <md-icon>assignment</md-icon>
                        </md-button>

                        <md-button v-if="user.is_admin" class="md-icon-button" @click="confirmDelete(job)">
                            <md-icon>delete</md-icon>
                        </md-button>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>
        </md-table >
        <md-table-pagination
                :md-size="options.pager.size"
                :md-total="totalJobs"
                :md-page="options.pager.page"
                md-label="Jobs"
                md-separator="of"
                :md-page-options="[20, 50, 100]"
                @pagination="onPagination"></md-table-pagination>

        <div class="margin-top-40">&nbsp;</div>
    </md-table-card>

    <md-dialog-confirm
            md-title="Delete this job?"
            md-content-html="This action can not be reversed and the job data will be permenantly deleted."
            md-ok-text="Delete Job"
            md-cancel-text="Cancel"
            @close="deleteJob"
            ref="delete-job">
    </md-dialog-confirm>


</div>
</template>

<script>
    export default {
        mounted() {
            console.log(' Admin jobs component ready.')
            this.refreshTable();
        },
        data: () => ({
            state: {
                searchSelected : false,
                loading: false,
                selectedJob : null,
            },
            columns: ['title','client','freelancer','agency','type','status','created_at'],
            defaultColumns: ['title','created_at'],
            labelColumns: ['type','status'],
            userColumns: ['agency','client','freelancer'],
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
                filter: null,
                admin: false
            },
            totalJobs: 5000,
            user: window.Laravel.user,
            searchTerm: null,
            jobs: [],
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
            jobLink: function (job) {
                return "/job/" + job.id;
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

                if(this.user.is_admin){
                    this.options.admin = true;
                } else {
                    this.options.admin = false;
                }

                this.$http.get('/apiv1/jobs', {params : this.options}).then((response) => {
                    this.jobs = response.body.data;
                    this.options.pager.page = response.body['current_page'];
                    this.totalJobs = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    console.log("Error loading jobs");
                    console.log(response);
                });
            },
            confirmDelete(job) {
                this.state.selectedJob = job;
                this.$refs['delete-job'].open();
            },
            deleteJob(confirmation) {
              if (confirmation !== 'ok') {
                  return;
              }

              if (!this.state.selectedJob || !this.state.selectedJob.id) {
                  this.$root.showNotification('Invalid job selected, please refresh the jobs and try again.');
              }

                this.$http.post('/apiv1/jobs/delete/' + this.state.selectedJob.id, this.state.selectedJob).then((response) => {

                    this.$root.showNotification(response.body.message);
                    this.refreshTable();

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log(response);
                });

              this.selectedJob = null;

            },
            showNotification(message) {
                this.$refs.jobsNotifyContainer.createSnackbar({
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
