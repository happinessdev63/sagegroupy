<template>
    <div>
        <md-list class="custom-list md-triple-line">
            <md-list-item v-for="(job,index) in shared.jobs">
                <div v-if="primaryImageLink(job)" class="margin-right-20">
                    <img :src="primaryImageLink(job)" class="img-thumbnail" width="80" height="80" alt="Primary Image">
                </div>

                <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                    <md-icon class="font-size-40" >
                        assignment
                    </md-icon>
                </div>


                <div class="md-list-text-container">
                    <a :href="jobLink(job)" class="link-primary margin-top-10">{{ job.title }}</a>
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
                            {{ job.client.name }} rated {{ job.freelancer.name}} {{ job.references[0].rating }} out of 5
                        </div>
                        <div class="clearfix"></div>
                        <br/>
                        <p class="margin-bottom-5">
                        {{ job.references[0].review }}
                        </p>
                    </div>
                    <p v-if="job.references.length == 0 || (!job.references[0].review && job.completed)">No review from the client yet.</p>
                </div>

                <div v-if="isJobEditable(job)">
                    <md-button class="md-icon-button md-list-action" @click="confirmDeleteJob(job)">
                        <md-icon class="md-primary">delete</md-icon>
                    </md-button>
                    <md-button class="md-icon-button md-list-action" :href="jobEditLink(job)">
                        <md-icon class="md-primary">edit</md-icon>
                    </md-button>
                </div>

                <md-divider class="md-inset-110"></md-divider>
            </md-list-item>
        </md-list>

        <div v-if="shared.jobs.length == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    This user has not added any reference jobs yet. <br/>You can contact them if you would like to see examples of their work.
                </h5>
                <md-button class="md-primary md-raised" @click="$root.contactUser(shared.profile)">
                    Contact {{ shared.profile.first_name }}
                </md-button>
            </div>
        </div>

        <md-dialog-confirm
                md-title="Delete this reference?"
                md-content-html="This action can not be reversed and the reference along with its data will be permenantly deleted."
                md-ok-text="Delete Reference"
                md-cancel-text="Cancel"
                @close="deleteJob"
                ref="delete-job">
        </md-dialog-confirm>
    </div>
</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log(' Profile references component ready.')
            this.options['user_id'] = this.profile.id;
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
                profile: window.Laravel.profile,
                shared: window.sageSource,
                totalJobs: 0,
                errors: {},
                options: {
                    'user_id': null,
                    'pager': {
                        page: 1,
                        size: 25
                    },
                    search: '',
                    filter: ""
                },
                state: {
                    showCompletedJobs: false,
                    selectedJob: {},
                    loading: true
                }
            }
        },
        computed: {
            showCompletedJobs: function () {
                return this.state.showCompletedJobs || this.totalJobs == this.totalCompleteJobs;
            },
            totalCompleteJobs: function () {
                var completeJobs = this.shared.jobs.filter(function (job) {
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

            	if (!this.shared.user.id) {
            		return false;
                }

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
                this.$http.get('/apiv1/jobs', {params: this.options}).then((response) => {
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