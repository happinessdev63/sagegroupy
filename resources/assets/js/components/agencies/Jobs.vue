<template>
    <div>
        <md-button class="md-primary pull-right" @click="$root.emitEvent('openFullModal','createReferenceModal')" target="_blank">
            <md-icon>add</md-icon>
            Add / Request Reference
        </md-button>

        <md-button v-if="isFiltered" class="md-primary pull-right" @click="clearFilters">
            <md-icon>clear</md-icon>
            Clear Filter
        </md-button>

        <md-menu md-size="4" class="pull-right">
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

        <!-- Hide for now, filters take care of this
        <md-button-toggle class="pull-right md-accent margin-top-5">
            <md-button class="md-primary" @click="state.showCompletedJobs = !state.showCompletedJobs">
                <md-icon class="md-primary">assignment_turned_in</md-icon>
                Show Completed Jobs
            </md-button>
        </md-button-toggle>
        -->


        <div class="clearfix"></div>

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


        <div v-if="totalJobs == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    Your agency does not have any jobs yet!
                    <br/><br/> Have you filled in your agency details? Include as many details as possible to increase your chances of getting invited to bid on jobs.
                </h5>

            </div>
        </div>

        <md-list class="custom-list md-triple-line margin-top-20" v-if="!state.loading">
            <md-list-item v-for="(job, key, index) in shared.jobs" v-if="job.completed != 1 ">
                <md-icon class="font-size-40">
                    assignment
                </md-icon>

                <div class="md-list-text-container margin-top-10 padding-bottom-5">
                    <div>
                        <span>
                            <span v-if="job.type == 'reference'"
                                  class="label label-primary pull-right margin-right-20 no-margin-xs">Reference Job</span>
                            <a :href="jobLink(job)" class="link-primary">{{ job.title }}</a></span>
                    </div>
                    <span>{{ job.created_at }}</span>
                    <p>{{ job.description | limitTo(140) }}</p>
                </div>

                <md-menu md-size="4">
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

                <md-divider class="md-inset"></md-divider>
            </md-list-item>

            <!-- Completed Jobs -->
            <div v-if="!state.loading">
                <md-list-item v-for="(job, key, index) in shared.jobs" v-if="job.completed == 1 ">
                    <md-icon class="font-size-40">
                        assignment_turned_in
                    </md-icon>

                    <div class="md-list-text-container margin-top-10 padding-bottom-5">
                        <div>
                            <span>
                                <span v-if="job.type == 'reference'"
                                      class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      Reference Job
                                </span>
                                <a :href="jobLink(job)" class="link-primary">{{ job.title }}</a></span>
                        </div>
                        <span>Created {{ job.start_date}}
                            <span v-if="job.invite_client">, client was invited to provide a reference.</span>
                            <span v-if="job.completed"> - Completed {{ job.end_date}}</span>
                        </span>
                        <div v-if="!job.completed">
                            <p>Job is still in progress. </p>
                        </div>
                        <div v-if="job.references.length > 0 && job.references[0].review">
                            <div class="pull-left">
                                <sage-view-rating :rating="job.references[0].rating"></sage-view-rating>
                            </div>
                            <div class="pull-left font-weight-200 margin-left-10 margin-top-5 display-inline-block">
                                Client rated {{ job.agency.name}} {{ job.references[0].rating }} out of 5
                            </div>
                            <div class="clearfix"></div>
                            <br/>
                            <p class="margin-bottom-5">
                                {{ job.references[0].review }}
                            </p>
                        </div>
                        <p v-if="job.references.length == 0 || (!job.references[0].review && job.completed)">No review from the client yet.</p>
                    </div>

                    <md-menu md-size="4">
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

                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </div>
            <md-list-item v-if="shared.jobs.length < totalJobs" class="text-center" @click="showAllJobs">
                <div class="md-list-text-container">
                    <md-button>
                        Show All Jobs...
                    </md-button>
                </div>
            </md-list-item>

        </md-list>
        <div class="clearfix"></div>

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
            console.log(' Agency Jobs component ready.')
            var vm = this;
            appBus.$on('endJob', function (job) {
                vm.onEndJob(job);
            });

			appBus.$on('referenceJob:saved', function () {
				vm.refreshJobs();
			});

            this.refreshJobs();
        },
        created() {

        },
        props: ['agencyId'],
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                totalJobs: 0,
                errors: {},
                options: {
                    'agency_id' : null,
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
            totalCompleteJobs: function() {
                var completeJobs = this.shared.jobs.filter(function(job) {
                   return job.completed === '1';
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

                if (job.agency_id == this.agencyId) {
                    return true;
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
                this.options['agency_id'] = this.shared.agency.id;
                this.$http.get('/apiv1/jobs', {params : this.options}).then((response) => {
                    this.shared.jobs = response.body.data;
                    this.totalJobs = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log("Error loading agency jobs.");
                    console.log(response);
                });
            }
        }
    }
</script>