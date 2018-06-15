<template>
    <div class="bg-white border-radius-5 margin-bottom-20 md-whiteframe-3dp">
        <h4 class="no-margin-bottom pull-left margin-left-20 margin-top-15">
            <md-icon class="font-size-20 margin-right-10">
                assignment
            </md-icon>
            Jobs
        </h4>

        <md-button v-if="isFiltered" class="md-primary pull-right" @click="clearFilters">
            <md-icon>clear</md-icon>
            Clear Filter
        </md-button>

        <md-menu md-size="4" class="pull-right tour-filter-jobs">
            <md-button class="md-primary" md-menu-trigger>
                <md-icon>filter_list</md-icon>
                Filter
            </md-button>

            <md-menu-content>
                <md-menu-item @click="onFilter('filter-all')">
                    <span id="filter-all">Show All</span>
                </md-menu-item>

                <md-menu-item @click="onFilter('filter-active')">
                    <span id="filter-active">In Progress</span>
                </md-menu-item>

                <md-menu-item @click="onFilter('filter-completed')">
                    <span id="filter-completed">Completed</span>
                </md-menu-item>

                <md-menu-item v-if="user.is_freelancer" @click="onFilter('filter-reference')">
                    <span id="filter-reference">Reference Jobs</span>
                </md-menu-item>

            </md-menu-content>
        </md-menu>

        <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
            <span class="font-size-22 font-weight-100 text-warning">{{ totalInProgressJobs }}</span><br/>
            <span class="font-size-16 font-weight-100">In Progress</span>
        </div>

        <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
            <span class="font-size-22 font-weight-100 text-danger">{{ totalReferenceJobs }}</span><br/>
            <span class="font-size-16 font-weight-100">Reference Jobs</span>
        </div>

        <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
            <span class="font-size-22 font-weight-100 text-primary">{{ totalCompleteJobs }}</span><br/>
            <span class="font-size-16 font-weight-100">Completed</span>
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

            <div v-if="totalJobs == 0 && !state.loading" class="padding-10">
                <div class="text-center well">
                    <h5 class="font-size-16">
                        <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                        <br/>
                        You do not have any jobs yet!
                        <br/><br/>
                        <span v-if="user.is_freelancer">
                             Make sure you fill out your profile and skills and include as many details as possible to increase your chances of getting invited to bid on new jobs.
                        </span>
                        <span v-else>
                            Create a new job & invite freelancers or agencies to get started.
                        </span>
                    </h5>
                    <md-button v-if="user.is_client" class="md-primary md-raised" href="/jobs/create">
                        Create a New Job
                    </md-button>
                    <md-button v-if="user.is_freelancer" class="md-primary md-raised" href="/jobs/createReference">
                        Request a Reference
                    </md-button>
                </div>
            </div>

            <md-table  v-if="totalJobs > 0">
                <md-table-header>
                    <md-table-row>
                        <md-table-head >Status</md-table-head>
                        <md-table-head >Job Title</md-table-head>
                        <md-table-head >Job Type</md-table-head>
                        <md-table-head >Client</md-table-head>
                        <md-table-head >Freelancer</md-table-head>
                        <md-table-head>Options</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(job, key, index) in shared.jobs" :key="index">
                        <md-table-cell>
                            <span v-if="job.completed != 1">In Progress</span>
                            <span v-else>Completed</span>
                        </md-table-cell>
                        <md-table-cell>
                            <a :href="jobLink(job)" class="link-primary font-weight-500 font-size-14">{{ job.title }}</a> <br/>
                            <!-- <span class="font-size-10 text-muted">{{ job.created_at }}</span> -->
                            <p>{{ job.short_description }}</p>
                        </md-table-cell>
                        <md-table-cell>
                            <span class="label label-primary" v-if="job.type == 'reference'">Reference Job</span>
                            <div v-else>
                                <span class="label label-primary">Standard Job</span><br/>
                                <span class="font-size-10 text-muted" v-if="job.nice_payment_terms">Payment Terms: {{job.nice_payment_terms}}</span>
                            </div>
                        </md-table-cell>
                        <md-table-cell>
                            <div v-if="job.client" class="text-center">
                                <md-avatar>
                                 <img :src="job.client.avatar" alt="job.client.name">
                                </md-avatar>
                                <br/>
                                <a class="font-weight-400 link-primary" :href="profileLink(job.client)">{{ job.client.name }}</a>
                            </div>
                            <span v-else>No Assigned Client</span>
                        </md-table-cell>
                        <md-table-cell >
                            <div v-if="job.freelancer" class="text-center">
                                <md-avatar>
                                    <img :src="job.freelancer.avatar" alt="job.freelancer.name">
                                </md-avatar>
                                <br/>
                                <a class="font-weight-400 link-primary" :href="profileLink(job.freelancer)">{{ job.freelancer.name }}</a>
                            </div>
                            <div v-if="job.agency" class="text-center margin-top-10">
                                <md-avatar>
                                    <img :src="job.agency.avatar" alt="job.agency.name">
                                </md-avatar>
                                <br/>
                                <a class="font-weight-400 link-primary" :href="`/agency/${job.agency.id}`">{{ job.agency.name }} <br/> (Agency)</a>
                            </div>
                            <span v-if="!job.freelancer && !job.agency">No Assigned Freelancer</span>

                        </md-table-cell>
                        <md-table-cell>
                            <md-menu md-size="4" v-if="job.completed != 1" md-direction="top left">
                                <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                    <md-icon clas="md-primary">more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item @click="endJob(job)">
                                        <md-icon clas="md-primary">assignment_turned_in</md-icon>
                                        <span>End Job</span>
                                    </md-menu-item>

                                    <md-menu-item v-if="isJobEditable(job)" @click="editJob(job)">
                                        <md-icon clas="md-primary">edit</md-icon>
                                        <span>Edit Job</span>
                                    </md-menu-item>

                                    <md-menu-item @click="viewJob(job)">
                                        <md-icon clas="md-primary">assignment</md-icon>
                                        <span>View Job</span>
                                    </md-menu-item>

                                    <md-menu-item @click="confirmDeleteJob(job)">
                                        <md-icon clas="md-primary">delete</md-icon>
                                        <span>Delete Job</span>
                                    </md-menu-item>

                                </md-menu-content>
                            </md-menu>

                            <md-menu md-size="4" v-else md-direction="top left">
                                <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                    <md-icon clas="md-primary">more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item @click="resumeJob(job)" v-if="job.type != 'reference'">
                                        <md-icon clas="md-primary">assignment_return</md-icon>
                                        <span>Resume Job</span>
                                    </md-menu-item>

                                    <md-menu-item v-if="isJobEditable(job)" @click="editJob(job)">
                                        <md-icon clas="md-primary">edit</md-icon>
                                        <span>Edit Job</span>
                                    </md-menu-item>

                                    <md-menu-item @click="viewJob(job)">
                                        <md-icon clas="md-primary">assignment</md-icon>
                                        <span>View Job</span>
                                    </md-menu-item>

                                    <md-menu-item @click="confirmDeleteJob(job)">
                                        <md-icon clas="md-primary">delete</md-icon>
                                        <span>Delete Job</span>
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
                md-title="Delete this job?"
                md-content-html="This action can not be reversed and the job along with its data will be permenantly deleted."
                md-ok-text="Delete Job"
                md-cancel-text="Cancel"
                @close="deleteJob"
                ref="delete-job">
        </md-dialog-confirm>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Jobs component ready.')
            this.options['user_id'] = this.user.id;
            this.refreshJobs();

            var vm = this;
            appBus.$on('endJob', function (job) {
                vm.onEndJob(job);
            });
        },
        created() {

        },
        props: ['role'],
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                totalJobs: 0,
                errors: {},
                options: {
                    'user_id' : null,
                    'pager' : {
                        page: 1,
                        size: 25
                    },
                    search: '',
                    filter: null
                },
                state: {
                    showCompletedJobs: false,
                    selectedJob : {},
                    loading: true
                }
            }
        },
        computed: {
            showCompletedJobs: function() {
              return this.state.showCompletedJobs || this.totalJobs == this.totalCompleteJobs;
            },
			totalReferenceJobs: function () {
				var refJobs = this.shared.jobs.filter(function (job) {
					return job.type === 'reference';
				});

				return refJobs.length;
			},
			totalInProgressJobs: function () {
				return this.shared.jobs.length - this.totalCompleteJobs;
			},
			totalCompleteJobs: function() {
                var completeJobs = this.shared.jobs.filter(function(job) {
                   return job.completed === true;
                });

                return completeJobs.length;
            },
            isFiltered: function () {
                return this.options.search || this.options.filter;
            },
            totalActiveJobs: function () {
                var activeJobs = this.shared.jobs.filter(function (job) {
                    return job.completed != '1';
                });

                return activeJobs.length;
            }
        },
        filters: {
            limitTo: function(string, value) {
                if (!string) {
                    return '';
                }

                if (string.length <= value) {
                    return string;
                }

                return string.substring(0, value) + '...';
            },
            capitalize: function(text) {
                return text[0].toUpperCase() + text.slice(1)
            }
        },
        methods: {
            onFilter(filter) {
                this.options.filter = filter;
                this.refreshJobs();
            },
            clearFilters() {
                this.options.search = '';
                this.options.filter = '';
                this.refreshJobs();
            },
            viewJob(job) {
                window.location = this.jobLink(job);
            },
            editJob(job) {
                window.location = this.jobEditLink(job);
            },
            resumeJob(job) {

            },
            /**
             * @todo - Check if user is agency owner and can edit job
             * @param job
             * @returns {boolean}
             */
            isJobEditable(job) {

                if (job.freelancer_id == this.user.id && job.type == "reference") {
                    return true;
                }

                if (job.completed === '1') {
                    return false;
                }

                if (job.client_id == this.user.id) {
                    return true;
                }

                return false;

            },
            confirmDeleteJob(job) {
                this.shared.job = job;
                this.$refs['delete-job'].open();
            },
            deleteJob(confirmation) {

                if (confirmation !== 'ok') {
                    return;
                }

                var apiUrl = '/apiv1/jobs/delete/' + this.shared.job.id;
                this.$http.post(apiUrl).then((response) => {
                    this.$root.showNotification(response.body.message);
                    this.refreshJobs();
                }, (response) => {
                    this.$root.showNotification(response.body.message);
                });
                console.log("deleting " + this.shared.job.title);
            },
            endJob(job) {
                this.shared.job = job;
                this.$root.$refs['endJob'].open();
            },
            onEndJob(event) {
                this.$root.$refs['endJob'].close();
                this.refreshJobs();
            },
            jobLink: function (job) {
                return "/job/" + job.id;
            },
            profileLink: function (profile) {
                return "/profile/" + profile.id;
            },
            jobEditLink: function (job) {
                if (job.type == "reference") {
                    return "/jobs/editReference/" + job.id;
                }

                return "/jobs/edit/" + job.id;
            },
            showAllJobs() {
              this.options.pager.size = 1000;
              this.refreshJobs();
            },
            refreshJobs()
            {
                this.state.loading = true;
                this.$http.get('/apiv1/jobs', {params : this.options}).then((response) => {
                    this.shared.jobs = response.body.data;
                    this.totalJobs = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log("Error loading jobs");
                    console.log(response);
                });
            }
        }
    }
</script>
