<template>
    <div>
        <md-button class="md-primary pull-right" @click="$root.emitEvent('openFullModal','createImportedReferenceModal')" target="_blank">
            <md-icon>add</md-icon>
            Import a Reference
        </md-button>
        <md-button class="md-primary pull-right" @click="$root.emitEvent('openFullModal','createGeneralReferenceModal')" target="_blank">
            <md-icon>add</md-icon>
            General Reference
        </md-button>
        <md-button class="md-primary pull-right" @click="$root.emitEvent('openFullModal','createReferenceModal')" target="_blank">
            <md-icon>add</md-icon>
            Request Job Reference
        </md-button>
        <div class="clearfix"></div>
        <h4>Job References</h4>
        <hr class="green-hr"/>
        <md-list class="custom-list md-triple-line">
            <md-list-item v-for="(job,index) in shared.jobs" :key="index">
                <div v-if="primaryImageLink(job)" class="margin-right-20">
                    <img :src="primaryImageLink(job)" class="img-thumbnail" width="80" height="80" alt="Primary Image">
                </div>

                <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                    <md-icon class="font-size-40" >
                        assignment
                    </md-icon>
                </div>


                <div class="md-list-text-container">
                    <a :href="jobLink(job)" target="_blank" class="link-primary">{{ job.title }}</a>
                    <span>Created {{ job.start_date }}<span v-if="job.invite_client">, client was invited to provide a reference.</span> </span>
                    <p v-if="job.references[0].review">
                        <span class="font-weight-200 text-accent">Client rated you {{ job.references[0].rating }} out of 5</span> <br/> {{ job.references[0].review }}</p>
                    <p v-else>No review from the client yet.</p>
                </div>

                <md-button class="md-icon-button md-list-action" @click="confirmDeleteJob(job)">
                    <md-icon class="md-primary">delete</md-icon>
                </md-button>
                <md-button class="md-icon-button md-list-action" :href="jobEditLink(job)">
                    <md-icon class="md-primary">edit</md-icon>
                </md-button>

                <md-divider class="md-inset-110"></md-divider>
            </md-list-item>
        </md-list>

        <div v-if="shared.jobs.length == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    You do not have any reference jobs yet!
                    <br/><br/> Reference jobs are a great way to showcase past work and include social proof of your skills as a freelancer.
                </h5>
                <md-button @click="$root.emitEvent('openFullModal','createReferenceModal')" target="_blank" class="md-primary md-raised">
                    Create a New Reference Job
                </md-button>
            </div>
        </div>

        <h4>General References</h4>
        <hr class="green-hr"/>
        <md-list class="custom-list md-triple-line">
            <md-list-item v-for="(reference,index) in shared.references" v-if="reference.type == 'reference'" :key="index">
                <div v-if="primaryImageLink(reference)" class="margin-right-20">
                    <img :src="primaryImageLink(reference)" class="img-thumbnail" width="80" height="80" alt="Primary Image">
                </div>

                <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                    <md-icon class="font-size-40">
                        class
                    </md-icon>
                </div>

                <div class="md-list-text-container">
                    <a :href="`/generalReference/${reference.id}`" target="_blank" class="link-primary">{{ reference.title }}</a>
                    <span>Added {{ reference.start_date }} </span>
                    <div>
                        {{ reference.short_description }}
                    </div>
                </div>

                <md-button class="md-icon-button md-list-action" @click="confirmDeleteReference(reference)">
                    <md-icon class="md-primary">delete</md-icon>
                </md-button>
                <md-button class="md-icon-button md-list-action"
                           @click="$root.$refs['generalReferenceEditor'].setReference(reference); $root.emitEvent('openFullModal','createGeneralReferenceModal')">
                    <md-icon class="md-primary">edit</md-icon>
                </md-button>

                <md-divider class="md-inset-110"></md-divider>
            </md-list-item>
        </md-list>

        <h4>Imported References</h4>
        <hr class="green-hr"/>
        <md-list class="custom-list md-triple-line">
            <md-list-item v-for="(reference,index) in shared.references" v-if="reference.type == 'imported'" :key="index">
                <div v-if="primaryImageLink(reference)" class="margin-right-20">
                    <img :src="primaryImageLink(reference)" class="img-thumbnail" width="80" height="80" alt="Primary Image">
                </div>

                <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                    <md-icon class="font-size-40">
                        class
                    </md-icon>
                </div>

                <div class="md-list-text-container">
                    <a :href="`/generalReference/${reference.id}`" target="_blank" class="link-primary">{{ reference.title }}</a>
                    <span>Added {{ reference.start_date }} </span>
                    <div>
                        {{ reference.short_description }}
                    </div>
                    <div v-if="reference.url && reference.url_description" class="margin-top-10">
                        <a class="link-primary" :href="reference.url" target="_blank" rel="nofollow">Visit Related Link ({{ reference.url_description}})</a>
                    </div>
                </div>

                <md-button class="md-icon-button md-list-action" @click="confirmDeleteReference(reference)">
                    <md-icon class="md-primary">delete</md-icon>
                </md-button>
                <md-button class="md-icon-button md-list-action"
                           @click="$root.$refs['importedReferenceEditor'].setReference(reference); $root.emitEvent('openFullModal','createImportedReferenceModal')">
                    <md-icon class="md-primary">edit</md-icon>
                </md-button>

                <md-divider class="md-inset-110"></md-divider>
            </md-list-item>
        </md-list>

        <div class="margin-top-20">
            <!-- Recommendations -->
            <h4 class="pull-left">Recommendations</h4>
            <md-button class="md-primary pull-right" @click.native="openParentModal('freelancerSearchModal')">
                <md-icon>person_add</md-icon>
                Add Recommended Freelancer
            </md-button>
            <md-button class="md-primary pull-right" @click.native="openParentModal('askForRecommendationModal')">
                <md-icon>person_add</md-icon>
                Ask For a Recommendation
            </md-button>
            <div class="clearfix"></div>
            <hr class="green-hr"/>
            <md-list class="custom-list md-dense">
                <md-list-item v-for="user in shared.profile.recommended_users" :key="index">

                    <img :src="user.freelancer.avatar" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <div class="margin-bottom-10">
                            <span> <a :href="user.freelancer.profile_link" class="link-primary">Recommends {{ user.freelancer.name}}</a></span>
                        </div>
                        <p v-if="user.snippet && user.snippet.length == 0">
                            {{ user.owner.name }} recommends {{ user.freelancer.name }} as an additional freelancer with complimentary skills.
                        </p>
                        <p v-else>{{ user.snippet }}</p>
                    </div>

                    <md-button v-if="shared.profile.id == user.owner.id" class="md-primary" @click="confirmDeleteRec(user)">
                        <md-icon class="md-primary">close</md-icon>
                    </md-button>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
                <md-list-item v-for="user in shared.profile.recommendations" :key="index">

                    <img :src="user.freelancer.avatar" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <div class="margin-bottom-10">
                            <span> <a :href="user.owner.profile_link" class="link-primary">You Were Recommended By {{ user.owner.name}}</a></span>
                        </div>
                        <p v-if="user.snippet && user.snippet.length == 0">
                            {{ user.owner.name }} recommended you as an additional freelancer with complimentary skills.
                        </p>
                        <p v-else>{{ user.snippet }}</p>
                    </div>

                    <md-button v-if="shared.profile.id == shared.user.id" class="md-primary" @click="confirmDeleteRec(user)">
                        <md-icon class="md-primary">close</md-icon>
                    </md-button>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <md-dialog-confirm
                md-title="Delete this reference?"
                md-content-html="This action can not be reversed and the reference along with its data will be permenantly deleted."
                md-ok-text="Delete Reference"
                md-cancel-text="Cancel"
                @close="deleteJob"
                ref="delete-job">
        </md-dialog-confirm>

        <md-dialog-confirm
                md-title="Delete this reference?"
                md-content-html="This action can not be reversed and the reference along with its data will be permenantly deleted."
                md-ok-text="Delete Reference"
                md-cancel-text="Cancel"
                @close="deleteReference"
                ref="delete-reference">
        </md-dialog-confirm>

        <md-dialog-confirm
                md-title="Delete this recommendation?"
                md-content-html="Are you sure you want to remove this recommendation from your profile?"
                md-ok-text="Delete Recommendation"
                md-cancel-text="Cancel"
                @close="deleteRec"
                ref="deleteRec">
        </md-dialog-confirm>
    </div>
</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log(' Profile references component ready.')
            this.options['user_id'] = this.user.id;
            this.refreshJobs();
			this.refreshReferences();

            var vm = this;
            appBus.$on('endJob', function (job) {
                vm.onEndJob(job);
            });

			appBus.$on('referenceJob:saved', () => {
				this.refreshJobs();
			});

			appBus.$on('generalReference:saved', () => {
				this.refreshReferences();
			});

			var vm = this;
			appBus.$off('addedRecommendation');
			appBus.$on('addedRecommendation', function (freelancer, snippet) {
				vm.addRecommendation(freelancer, snippet);
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
                    'user_id': null,
                    'pager': {
                        page: 1,
                        size: 25
                    },
                    search: '',
                    filter: "filter-reference"
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
			addRecommendation(freelancer, snippet) {
				this.$http.post('/apiv1/profile/addRec/', {
					to_user_id: freelancer.id,
					from_user_id: this.shared.user.id,
					snippet: snippet
				}).then((response) => {
					this.$root.showNotification(response.body.message);
					appBus.$emit('refreshProfile');
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
			confirmDeleteRec(rec) {
				this.state.selectedRec = rec;
				this.$refs['deleteRec'].open();
			},
			deleteRec(confirmation) {
				if (confirmation !== 'ok') {
					return;
				}

				var apiUrl = '/apiv1/profile/rec/delete/' + this.state.selectedRec.id;
				this.$http.get(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					appBus.$emit('refreshProfile');
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
			},
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
			confirmDeleteReference(reference) {
				this.shared.reference = reference;
				this.$refs['delete-reference'].open();
			},
			deleteReference(confirmation) {

				if (confirmation !== 'ok') {
					return;
				}

				var apiUrl = '/apiv1/references/delete/' + this.shared.reference.id;
				this.$http.post(apiUrl).then((response) => {
					this.$root.showNotification(response.body.message);
					this.refreshReferences();
				}, (response) => {
					this.$root.showNotification(response.body.message);
				});
				console.log("deleting " + this.shared.reference.title);
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
            },
			refreshReferences()
			{
				this.state.loading = true;
				this.$http.get('/apiv1/references', {params: this.options}).then((response) => {
					this.shared.references = response.body.data;
					this.state.loading = false;

				}, (response) => {
					this.$root.showNotification(response.body.message);
					console.log("Error loading references.");
					console.log(response);
				});
			},
			openParentModal(ref) {
				appBus.$emit('openParentModal', ref);
			},
        }
    }
</script>
