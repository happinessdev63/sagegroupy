<template>
    <div>
        <ui-modal ref="agencyInviteModal" transition="ui-modal-fade">
            <div slot="header">
                <h4 v-if="shared.freelancer" class="modal-title">Invite {{shared.freelancer.name}} To Your Agency</h4>
            </div>
            <div v-if="user.owned_agencies && user.owned_agencies.length > 0">
                <p>Select an agency you would like to invite this freelancer join. </p>
                <md-input-container>
                    <label>Select an Agency</label>
                <md-select required v-model="invite.agency_id" placeholder="Please select an agency.">
                    <md-option v-for="agency in user.owned_agencies" :value="agency.id">{{ agency.name }}</md-option>
                </md-select>
                </md-input-container>
            </div>

            <div v-else class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    You do not have any agencies.
                    <br/><br/> Before you can invite freelancers you need to create an agency.
                </h5>
            </div>
            <div slot="footer">
                <md-button class="md-primary" @click="$refs['inviteModal'].close()">Close</md-button>
                <md-button class="md-primary" v-if="user.owned_agencies && user.owned_agencies.length > 0" @click="sendInvite">Send Invite</md-button>
            </div>
        </ui-modal>
    </div>
</template>


<script type="text/babel">
	export default {
		mounted() {
			console.log('Agency Invite component ready.')
			let vm = this;
			appBus.$on('openAgencyInvite', (freelancer) => {
				console.log('opening agency freelancer invite');
				vm.open(freelancer);
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
			open(freelancer) {
				this.shared.freelancer = freelancer;
				this.refreshProfile();
				this.$refs['agencyInviteModal'].open();
				this.$forceUpdate();
			},
			sendInvite() {

				if (!this.invite.agency_id) {
					this.$root.showNotification(`Please select an agency to invite this freelancer to.`);
					return;
				}

				this.state.sending = true;

				var apiUrl = '/apiv1/agencies/invite/' + this.invite.agency_id

				this.$http.post(apiUrl, {'user_id': this.shared.freelancer.id}).then((response) => {
					this.state.saving = false;
					this.$root.showNotification(response.body.message);
					this.$refs['agencyInviteModal'].close();
				}, (response) => {
					this.state.saving = false;
					if (response.body.status == 'error' && response.body.message) {
						this.$root.showNotification(response.body.message);
					}
				});
			},
            refreshProfile() {
                this.$http.get('/apiv1/profile/' + this.shared.user.id).then((response) => {
                    this.user = response.body.user;
                }, (response) => {
                    this.$root.showNotification(response.body.message);
                });
            },
		}
	}
</script>