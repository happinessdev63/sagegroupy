<template>
    <div class="bg-white border-radius-5 margin-bottom-20  md-whiteframe-3dp">
        <h4 class="no-margin-bottom pull-left margin-left-20 margin-top-15">Agencies</h4>

        <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
            <span class="font-size-22 font-weight-100 text-primary">{{ shared.user.owned_agencies.length }}</span><br/>
            <span class="font-size-16 font-weight-100">Agencies You Own</span>
        </div>

        <div class="pull-right text-center margin-top-20 margin-left-10 margin-right-10">
            <span class="font-size-22 font-weight-100 text-danger">{{ shared.user.agencies.length }}</span><br/>
            <span class="font-size-16 font-weight-100">Member of Agencies</span>
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

            <div v-if="shared.user.agencies.length === 0 && shared.user.owned_agencies.length === 0" class="padding-10">
                <div class="text-center well">
                    <h5 class="font-size-16">
                        <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                        <br/>
                        You do not belong to any agencies yet.
                        <br/><br/> Joining an agency is a great way to learn new skills, get more exposure and land bigger jobs!
                    </h5>
                    <md-button href="/agencies/create" class="md-primary md-raised">
                        Create a New Agency
                    </md-button>
                    <md-button href="/agencies" class="md-primary md-raised">
                        Join an Agency
                    </md-button>
                </div>
            </div>

            <md-table v-if="!state.loading && (shared.user.agencies.length > 0 || shared.user.owned_agencies.length > 0)">
                <md-table-header>
                    <md-table-row>
                        <md-table-head></md-table-head>
                        <md-table-head>Agency Details</md-table-head>
                        <md-table-head class="text-center" style="min-width: 150px;">Owner</md-table-head>
                        <md-table-head style="min-width: 300px;">Members</md-table-head>
                        <md-table-head>Options</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(agency, key, index) in allAgencies">
                        <md-table-cell>
                            <md-icon class="font-size-30 text-muted">
                                card_membership
                            </md-icon>
                        </md-table-cell>
                        <md-table-cell>
                            <a :href="agencyLink(agency)" class="link-primary font-weight-600 font-size-14">{{ agency.name }}</a>
                            <br/>
                            <span>{{ agency.freelancers.length + 1}} Member(s)</span><br/>
                            <p>{{ agency.short_description }}</p>
                        </md-table-cell>
                        <md-table-cell>
                            <div v-if="agency.owned" class="text-center">
                                <md-avatar>
                                    <img :src="shared.user.avatar" alt="shared.user.name">
                                </md-avatar>
                                <br/>
                                <a class="font-weight-400 link-primary" :href="profileLink(shared.user)">{{ shared.user.name }}</a>
                            </div>
                            <div v-else class="text-center">
                                <md-avatar>
                                    <img :src="agency.owner.avatar" alt="agency.owner.name">
                                </md-avatar>
                                <br/>
                                <a class="font-weight-400 link-primary" :href="profileLink(agency.owner)">{{ agency.owner.name }}</a>
                            </div>
                        </md-table-cell>
                        <md-table-cell>
                            <ul class="list-group" v-if="agency.freelancers.length > 0">
                                <li class="list-group-item" v-for="user in agency.freelancers">
                                    <md-avatar class="pull-left margin-right-10">
                                        <img :src="user.avatar" alt="user.name">
                                    </md-avatar>
                                    <div class="margin-left-10 margin-top-10">
                                        <a class="font-weight-400 link-primary" :href="profileLink(user)">{{ user.name }}</a>
                                        <div class="clearfix"></div>
                                    </div>
                                </li>
                            </ul>

                            <div v-if="agency.freelancers.length == 0">
                                <span class="label label-primary">No Agency Members</span>
                            </div>
                        </md-table-cell>
                        <md-table-cell>
                            <md-menu md-size="4" v-if="agency.owned" md-direction="top left">
                                <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                    <md-icon clas="md-primary">more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item v-if="isAgencyEditable(agency)" @click="editAgency(agency)">
                                        <md-icon clas="md-primary">edit</md-icon>
                                        <span>Edit Agency</span>
                                    </md-menu-item>

                                    <md-menu-item @click="viewAgency(agency)">
                                        <md-icon clas="md-primary">assignment</md-icon>
                                        <span>View Agency</span>
                                    </md-menu-item>

                                </md-menu-content>
                            </md-menu>

                            <md-menu md-size="4" v-else md-direction="top left">
                                <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                    <md-icon clas="md-primary">more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item @click="editAgency(agency)">
                                        <md-icon clas="md-primary">edit</md-icon>
                                        <span>Manage Agency</span>
                                    </md-menu-item>

                                    <md-menu-item @click="viewAgency(agency)">
                                        <md-icon clas="md-primary">assignment</md-icon>
                                        <span>View Agency</span>
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

    </div>
</template>

<script type="text/babel">
	export default {
		mounted() {
			console.log(' User agencies component ready.')
			this.state.loading = false;
		},
		created() {

		},
		props: [],
		data() {
			return {
				user: window.Laravel.user,
				shared: window.sageSource,
				totalAgencies: 0,
				errors: {},
				options: {
					'user_id': null,
					'pager': {
						page: 1,
						size: 25
					},
					search: '',
					filter: null
				},
				state: {
					showCompletedAgencies: false,
					selectedAgency: {},
					loading: true
				}
			}
		},
		computed: {
			allAgencies: function () {
				var allAgencies = [];
				this.shared.user.owned_agencies.forEach(function (agency) {
					agency.owned = true;
					allAgencies.push(agency);
				});

				this.shared.user.agencies.forEach(function (agency) {
					agency.owned = false;
					allAgencies.push(agency);
				});
				return allAgencies;
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
			onFilter(filter) {
				this.options.filter = filter;
				this.refreshAgencies();
			},
			clearFilters() {
				this.options.search = '';
				this.options.filter = '';
				this.refreshAgencies();
			},
			viewAgency(agency) {
				window.location = this.agencyLink(agency);
			},
			editAgency(agency) {
				window.location = this.agencyEditLink(agency);
			},
			refreshUser() {
				this.state.loading = true;
				this.$http.get('/apiv1/users/' + this.shared.profile.id).then((response) => {
						this.shared.profile = response.body.user;
						this.state.loading = false;
					}, (response) => {
						this.$root.showNotification(response.body.message);
					}
				);
			},
			isAgencyEditable(agency) {

				if (agency.owner_id == this.user.id) {
					return true;
				}

				return false;

			},
			profileLink: function (profile) {
				return "/profile/" + profile.id;
			},
			agencyLink: function (agency) {
				return "/agency/" + agency.id;
			},
			agencyEditLink: function (agency) {
				return "/agencies/edit/" + agency.id;
			}
		}
	}
</script>