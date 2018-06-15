<template>
    <div>
        <ui-modal ref="inviteModal" transition="ui-modal-fade">
            <div slot="header">
                <h4 v-if="shared.freelancer" class="modal-title">Invite {{shared.freelancer.name}} To Your Job</h4>
            </div>
            <div v-if="shared.jobs.length > 0">
                <p>Select a job you would like to invite this {{ label }} to apply for. You should include a message to the {{ label }} that include details of the invite, extra job details or any questions you may have. </p>
                <md-input-container>
                    <label>Select a Job</label>
                <md-select required name="job_id" id="job_id" v-model="invite.job_id" placeholder="Please select a job.">
                    <md-option v-for="job in shared.jobs" :value="job.id" :key="job.id">{{ job.title }}</md-option>
                </md-select>
                </md-input-container>
                <md-input-container class="margin-top-20">
                    <label>Please enter a message to the freelancer.</label>
                    <md-textarea v-model="invite.message" required></md-textarea>
                </md-input-container>
            </div>

            <div v-else class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    You do not have any currently active jobs.
                    <br/><br/> Before you can invite freelancers or agencies you need to add one or more jobs.
                </h5>
            </div>
            <div slot="footer">
                <md-button class="md-primary" @click="$refs['inviteModal'].close()">Close</md-button>
                <md-button class="md-primary" v-if="shared.jobs.length > 0" @click="sendInvite">Send Invite</md-button>
            </div>
        </ui-modal>
    </div>
</template>

<script type="text/babel">
	export default {
		mounted() {
			console.log('Invite component ready.')
			let vm = this;
			appBus.$on('openInvite', (freelancer, isAgency) => {
				console.log('opening freelancer invite');
				vm.open(freelancer, isAgency);
			});
		},
		created() {

		},
		data() {
			return {
				user: window.Laravel.user,
				shared: window.sageSource,
				errors: {},
				recurringRefresh: null,
				options: {
					'user_id': null,
					'pager': {
						page: 1,
						size: 100
					},
					search: '',
					filter: 'filter-active'
				},
				invite: {
					from_user_id: null,
					job_id: '',
					freelancer_id: null,
					message: '',
				},
				state: {
					loading: true,
					sending: false,
                    isAgency: false
				}
			}
		},
		computed: {

			label: function() {
				if (this.state.isAgency) {
					return 'agency';
                }

                return 'freelancer';
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
			open(freelancer, isAgency) {

				if (isAgency) {
					this.state.isAgency = true;
					this.invite.agency_id = freelancer.id;
                } else {
					this.state.isAgency = false;
					this.invite.freelancer_id = freelancer.id;
                }

				this.shared.freelancer = freelancer;
				this.invite.from_user_id = this.user.id;
				this.options.user_id = this.user.id;
				this.refreshJobs();
				this.$refs['inviteModal'].open();
				this.$forceUpdate();
			},
			sendInvite() {

				if (!this.invite.message) {
					this.$root.showNotification("Please enter a message to send along with your invite.");
					return;
				}

				if (!this.invite.job_id) {
					this.$root.showNotification(`Please select a job to invite this ${this.label} to.`);
					return;
				}

				this.state.sending = true;

				this.$http.post('/apiv1/jobs/invite/' + this.invite.job_id, this.invite).then((response) => {

					this.state.sending = false;
					this.$root.showNotification(response.body.message);
					this.$refs['inviteModal'].close();

				}, (response) => {
					this.state.sending = false;
					this.$root.showNotification(response.body.message);
					console.log("Error sending message.");
					console.log(response);
				});
			},
			refreshJobs()
			{
				this.state.loading = true;
				this.$http.get('/apiv1/jobs', {params: this.options}).then((response) => {
					this.shared.jobs = response.body.data;
					this.state.loading = false;
					this.$forceUpdate();

				}, (response) => {
					this.$root.showNotification(response.body.message);
					console.log("Error loading available jobs");
					console.log(response);
				});
			}
		}
	}
</script>
