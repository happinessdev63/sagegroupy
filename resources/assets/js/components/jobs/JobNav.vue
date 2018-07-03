<template>
    <!-- End Job Modal -->
    <div v-cloak>
        <md-whiteframe md-elevation="3" class="padding-20  bg-white">

                <!-- Standard Job UI -->
                <div v-if="!isReferenceJob">
                    <!-- Show client information to everyone but the client -->
                    <div v-if="(!isOwner && hasClient) || (state.previewAsFreelancer && hasClient)">
                        <h4 class="no-margin-top">Client Details</h4>
                        <md-avatar class="md-large">
                            <img :src="shared.job.client.avatar" alt="People">
                        </md-avatar>

                        <div class="pull-right text-left margin-bottom-20">
                            <h5 class="margin-bottom-5 no-margin-top">{{ shared.job.client.name }}</h5>
                            <span class="text-muted">Joined {{ shared.job.client.nice_date }}</span> <br/>
                            <label class="label label-primary padding-5">Client </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="">
                            <ul class="list-group">
                                <li class="list-group-item">
                            <span class="font-weight-500">
                                Location
                            </span>
                                    <span class="pull-right text-right">
                                    {{ shared.job.client.country }}, {{ shared.job.client.city }}
                            </span>
                                    <div class="clearfix"></div>
                                </li>
                                <li class="list-group-item">
                            <span class="font-weight-500">
                                Company
                            </span>
                                    <span class="pull-right text-right">
                                        N/A
                             </span>
                                    <div class="clearfix"></div>
                                </li>
                                <li class="list-group-item">
                            <span class="font-weight-500">
                                Jobs Posted
                            </span>
                                    <span class="pull-right text-right label label-primary padding-5-10">
                                        3
                                </span>
                                    <div class="clearfix"></div>
                                </li>
                            </ul>
                        </div>
                        <hr class="green-hr"/>
                    </div>

                    <!-- Show freelancer info to client only -->
                    <div v-if="hasFreelancer">
                        <h4 class="no-margin-top">Freelancer Details</h4>
                        <md-avatar class="md-large">
                            <img :src="shared.job.freelancer.avatar" alt="People">
                        </md-avatar>
                        <div class="pull-right text-left margin-bottom-20">
                            <h5 class="margin-bottom-5 no-margin-top">{{ shared.job.freelancer.name }}</h5>
                            <span class="text-muted">Joined {{ shared.job.freelancer.nice_date}}</span> <br/>
                            <label class="label label-primary padding-5">Freelancer </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Location
                                    </span>
                                    <span class="pull-right text-right">
                                            {{ shared.job.freelancer.country }}, {{ shared.job.freelancer.city }}
                                    </span>
                                    <div class="clearfix"></div>
                                </li>
                                <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Company
                                    </span>
                                    <span class="pull-right text-right">
                                        N/A
                                    </span>
                                    <div class="clearfix"></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="isOwner && !hasFreelancer" class="text-center">
                        <h4 class="no-margin-top">Job Not Assigned Yet</h4>
                        <div class="margin-bottom-20">
                            <md-button href="/search" class="md-raised md-primary">Invite Freelancers</md-button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <hr/>
                </div>

                <!-- Reference Job UI with freelancer -->
                <div v-if="isReferenceJob && shared.job.freelancer">
                    <h4 class="no-margin-top">Reference Job</h4>
                    <md-avatar class="md-large">
                        <img :src="shared.job.freelancer.avatar" alt="People">
                    </md-avatar>
                    <div class="pull-right text-left margin-bottom-20">
                        <h5 class="margin-bottom-5 no-margin-top">{{ shared.job.freelancer.name }}</h5>
                        <span class="text-muted">Joined {{ shared.job.freelancer.created_at}}</span> <br/>
                        <label class="label label-primary padding-5">Freelancer </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <ul class="list-group">
                            <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Location
                                    </span>
                                <span class="pull-right text-right">
                                            {{ shared.job.freelancer.country }}, {{ shared.job.freelancer.city }}
                                    </span>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Company
                                    </span>
                                <span class="pull-right text-right">
                                        N/A
                                    </span>
                                <div class="clearfix"></div>
                            </li>
                        </ul>
                    </div>
                    <hr/>
                    <md-button :href="editUrl" v-if="isOwner && isReferenceJob"
                               class="md-primary md-raised btn-block no-margin-left no-margin-right">
                        Request a Reference
                    </md-button>
                </div>

                <!-- Reference Job UI with agency -->
                <div v-if="isReferenceJob && shared.job.agency">
                <h4 class="no-margin-top">Reference Job</h4>
                <md-avatar class="md-large">
                    <img :src="shared.job.agency.avatar" alt="People">
                </md-avatar>
                <div class="pull-right text-left margin-bottom-20">
                    <a class="margin-bottom-5 no-margin-top font-weight-600 link-primary" :href="`/agency/${shared.job.agency.id}`">{{ shared.job.agency.name }}</a> <br/>
                    <span class="text-muted">Formed {{ shared.job.agency.created_at}}</span> <br/>
                    <label class="label label-primary padding-5">Agency</label>
                </div>
                <div class="clearfix"></div>
                <div class="">
                    <ul class="list-group">
                        <li class="list-group-item">
                                    <span class="font-weight-500">
                                        Location
                                    </span>
                            <span class="pull-right text-right">
                                            {{ shared.job.agency.country }}, {{ shared.job.agency.city }}
                                    </span>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </div>
                <hr/>
                <md-button :href="editUrl" v-if="isOwner && isReferenceJob"
                           class="md-primary md-raised btn-block no-margin-left no-margin-right">
                    Request a Reference
                </md-button>
            </div>

                <!-- Client Actions -->
                <div v-if="isOwner && !isReferenceJob">
                    <md-button v-if="!shared.job.completed"
                               class="md-raised md-primary btn-block no-margin-left no-margin-right"
                               @click="endJob(shared.job)">
                        End Job
                    </md-button>
                    <button v-else class="btn btn-block btn-primary margin-bottom-10">
                        Job ended {{ shared.job.end_date}}
                    </button>
                    <md-button v-if="shared.job.completed && shared.job.references.length == 0  && hasFreelancer"
                               class="md-raised md-primary btn-block" @click="endJob(shared.job)">
                        Provide Freelancer Feedback
                    </md-button>
                </div>

                <!-- Freelancer Actions -->
                <div v-if="isFreelancer && !isReferenceJob" v-cloak>
                    <md-button v-if="!shared.job.completed"
                               class="md-raised md-primary btn-block no-margin-left no-margin-right"
                               @click="endJob(shared.job)">
                        End Job
                    </md-button>
                    <button v-else class="btn btn-block btn-succes  btn-primary margin-bottom-10">
                        Job ended {{ shared.job.end_date}}
                    </button>
                </div>

                <sage-share-job></sage-share-job>
                <div class="clearfix"></div>

            </md-whiteframe>
    </div>

</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log('Main job component ready.');
            if (this.job) {
                this.syncJob(this.job);
            }

            var vm = this;
            appBus.$on('endJob', function (job) {
                vm.onEndJob(job);
            });

        },
        props: ['job'],
        data() {
            return {
                errors: {},
                user: window.Laravel.user,
                shared: window.sageSource,
                state: {
                    previewAsFreelancer: false
                }
            }
        },
        computed: {
            hasClient: function () {
                if (this.shared.job.client) {
                    return true;
                }

                return false;
            },
            hasFreelancer: function () {
                if (this.shared.job.freelancer) {
                    return true;
                }

                return false;
            },
            hasFeedback: function () {
                if (this.shared.job.freelancer) {
                    return true;
                }

                return false;
            },
            isReferenceJob: function () {
                if (this.shared.job.type === 'reference') {
                    return true;
                }

                return false;
            },
            isOwner: function () {
                if (this.hasClient) {
                    return this.shared.job.client.id === this.user.id;
                }

                return this.isReferenceJob && this.isFreelancer;

            },
            isFreelancer: function () {
                return this.hasFreelancer && this.shared.job.freelancer.id === this.user.id;
            },
            editUrl: function() {
                if (this.isReferenceJob) {
                    return "/jobs/editReference/" + this.shared.job.id;
                } else {
                    return "/jobs/edit/" + this.shared.job.id;
                }
            }
        },
        methods: {
            syncJob: function () {
                this.$http.get('/apiv1/jobs/' + this.job, this.job).then((response) => {
                    this.shared.job = response.body.results;
                }, (response) => {
                    if (response.body.status == 'error' && response.body.message) {
                        //this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }
                });
            },
            endJob(job) {
                this.shared.job = job;
                this.$root.$refs['endJob'].open();
            },
            onEndJob(event) {
                this.$root.$refs['endJob'].close();
                window.location.reload(true);
            },
        }
    }
</script>
