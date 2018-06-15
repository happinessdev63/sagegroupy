<template>
    <div>
        <!-- <h4 class="pull-left">Search For Freelancers (Found {{ shared.freelancers.length }})</h4> -->
      <md-table-card>
        <div class="pull-right">
          <md-toolbar>
            <h1 class="md-title text-black">Freelancers (Found {{ shared.freelancers.length }})</h1>
            <md-button class="md-icon-button md-primary" @click="refreshFreelancers()">
                <md-icon>cached</md-icon>
            </md-button>

            <md-button v-if="!state.searchSelected" class="md-icon-button md-primary" @click="state.searchSelected = !state.searchSelected">
                <md-icon>search</md-icon>
            </md-button>

            <md-button v-if="isFiltered || state.searchSelected" class="md-primary" @click="clearFilters">
                <md-icon>clear</md-icon>
                Clear Filter
            </md-button>

            <md-button v-if="!state.showAgencies" class="md-primary" @click="state.showAgencies = true">
                <md-icon>visibility</md-icon>
                Show Agencies
            </md-button>

            <md-button v-if="state.showAgencies" class="md-primary" @click="state.showAgencies = false">
                <md-icon>visibility_off</md-icon>
                Hide Agencies
            </md-button>

            <!-- @todo - Hide filters for now until I can determine any useful fitlers
            <md-menu md-size="4" class="pull-right">
                <md-button class="md-primary" md-menu-trigger>
                    <md-icon>filter_list</md-icon>
                    Filter
                </md-button>

                <md-menu-content>
                    <md-menu-item @click="onFilter('filter-all')">
                        <span id="filter-all">Show All</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-own-freelancers')">
                        <span id="filter-own-freelancers">Your Freelancers</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-eligible-freelancers')">
                        <span id="filter-eligible-freelancers">Eligible Freelancers</span>
                    </md-menu-item>

                </md-menu-content>
            </md-menu>
            -->

          </md-toolbar>
        </div>

        <div class="clearfix"></div>

        <div v-if="state.searchSelected" class="col-xs-12">
            <div class="col-xs-6">
                <md-input-container>
                    <label>Search By User</label>
                    <md-input v-model="options.search" @change="onSearch"></md-input>
                </md-input-container>
            </div>
            <div class="col-xs-6">
                <ui-autocomplete
                        :autofocus="true"
                        :limit="8"
                        label="Search for a specfic skill."
                        name="new_skill"
                        placeholder="Start typing..."
                        :filter="autoCompleteMatch"
                        :suggestions="availableSkills"
                        :minChars="1"
                        v-model="state.selectedSkill"
                        @select="addSelectedSkill"
                ></ui-autocomplete>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-6">
                <ui-autocomplete
                        :autofocus="true"
                        :limit="12"
                        label="Country"
                        name="Country"
                        :suggestions="countries"
                        :filter="autoCompleteMatch"
                        placeholder="Start typing..."
                        :minChars="1"
                        value=""
                        v-model="options.country"
                        @input="updateCitySelect"
                ></ui-autocomplete>
            </div>
            <div class="col-xs-6">
                <ui-autocomplete
                        :autofocus="true"
                        :limit="12"
                        label="City"
                        name="city"
                        :filter="autoCompleteMatch"
                        :suggestions="cities"
                        :placeholder="cityPlaceholder"
                        :minChars="1"
                        value=""
                        v-model="options.city"
                ></ui-autocomplete>
            </div>
        </div>
        <div v-if="options.skills.length > 0" class="col-xs-12">
            <div class="margin-top-5 margin-top-sm-15 margin-top-xs-15 border-black" v-for="(skill,index) in options.skills">
                <div class="padding-5 border-1 border-grey-100 bg-grey-100 border-radius-5">
                    <div class="col-xs-4">
                        <h5>{{ skill.name }}</h5>
                    </div>
                    <div class="col-xs-6">
                        <md-select
                                required
                                name='level'
                                v-model="skill.level"
                                placeholder="Select an experience level"
                        >
                            <md-option
                                       value="any" >Any Level
                            </md-option>
                            <md-option v-for="(level,id) in shared.defaultSkill.avg_rates"
                                       :value="level.level" v-if="level.level != 'Total'"
                                       :key="id">{{ level.level }}
                            </md-option>
                        </md-select>
                    </div>
                    <div class="col-xs-2">
                        <a class="text-black" @click.prevent="removeSkillFilter(index)">
                            <md-icon  class="pull-right margin-top-5 cursor-pointer">clear</md-icon>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="col-xs-12" v-if="state.searchSelected">
            <md-button class="pull-right md-primary md-raised" @click="refreshFreelancers">Search</md-button>
        </div>


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


        <div v-if="totalFreelancers == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    We did not find any freelancers matching your search criteria.
                </h5>

            </div>
        </div>

        <md-list class="custom-list md-triple-line margin-top-20" v-if="!state.loading">
            <div v-if="!state.loading">
                <div v-for="(freelancer, key, index) in shared.freelancers">

                    <md-list-item>
                        <img :src="freelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;"
                             :alt="freelancer.name">

                        <div class="md-list-text-container margin-top-10 padding-bottom-5 margin-left-10">
                            <div>
                            <span>
                                <span class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      Freelancer
                                </span>
                                <span class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      Joined {{ freelancer.nice_date }}
                                </span>
                                <a :href="freelancerLink(freelancer)" target="_blank" class="link-primary">{{ freelancer.name }}</a>
                            </span>
                            </div>
                            <span>{{ freelancer.city }}, {{ freelancer.country }}</span>
                            <p> {{ freelancer.tagline }}</p>
                            <div>
                        <span v-for="skill in freelancer.minimal_skills" v-if="isMatchedSkill(skill)" class="label bg-grey-100 text-black pull-left padding-5 margin-top-10 margin-right-10">
                              {{ skill.name }} - ${{ skill.pivot.rate }}/hr ({{ skill.pivot.level }})
                        </span>
                            </div>
                        </div>

                        <md-menu md-size="5">
                            <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                <md-icon clas="md-primary">more_vert</md-icon>
                            </md-button>

                            <md-menu-content>
                                <md-menu-item @click="viewFreelancer(freelancer)">
                                    <md-icon class="md-primary">person</md-icon>
                                    <span>View Freelancer</span>
                                </md-menu-item>
                                <md-menu-item v-if="intent != 'agency-invite'" @click="inviteFreelancer(freelancer)">
                                    <md-icon class="md-primary">card_membership</md-icon>
                                    <span>Invite To Your Job</span>
                                </md-menu-item>
                                <md-menu-item v-if="intent == 'agency-invite'" @click="inviteFreelancerAgency(freelancer)">
                                    <md-icon class="md-primary">card_membership</md-icon>
                                    <span>Invite To Your Agency</span>
                                </md-menu-item>

                            </md-menu-content>
                        </md-menu>

                        <md-divider class="md-inset-110"></md-divider>
                    </md-list-item>
                    <md-list-item v-if="freelancer.agencies.length > 0 && state.showAgencies" v-for="(agency, key, index) in freelancer.agencies" :key="index">
                        <img :src="agency.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;"
                             :alt="agency.name">

                        <div class="md-list-text-container margin-top-10 padding-bottom-5 margin-left-10">
                            <div>
                            <span>
                                <span class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      Related Agency
                                </span>
                                <a :href="`/agency/${agency.id}`" target="_blank" class="link-primary">{{ agency.name }}</a>
                            </span>
                            </div>
                            <span>{{ agency.city }}, {{ agency.country }}</span>
                            <p> {{ agency.short_description }}</p>
                            <div>
                            </div>
                        </div>

                        <md-menu md-size="4">
                            <md-button class="md-icon-button md-list-action" md-menu-trigger>
                                <md-icon clas="md-primary">more_vert</md-icon>
                            </md-button>

                            <md-menu-content>
                                <md-menu-item @click="viewAgency(agency)">
                                    <md-icon class="md-primary">person</md-icon>
                                    <span>View Agency</span>
                                </md-menu-item>
                                <md-menu-item @click="inviteAgency(agency)">
                                    <md-icon class="md-primary">card_membership</md-icon>
                                    <span>Invite To Your Job</span>
                                </md-menu-item>

                            </md-menu-content>
                        </md-menu>

                        <md-divider class="md-inset-110"></md-divider>
                    </md-list-item>
                </div>
            </div>

        </md-list>
        <div class="clearfix"></div>
      </md-table-card>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Freelancer listings component ready.')
            var vm = this;
            this.refreshFreelancers();
            this.refreshSkills();

            if (this.intent == 'agency-invite') {
            	this.state.showAgencies = false;
            }
        },
        props: ['intent'],
        created() {
            /* Load countries and cities */
			this.$http.get('/js/countries.json').then((response) => {
				this.countries = response.body;
				let countryList = [];
				Object.keys(this.countries.countries).forEach((key) => {
					countryList.push({
						name: this.countries.countries[key].name,
						label: this.countries.countries[key].name,
						value: this.countries.countries[key].name,
						continent: this.countries.countries[key].continent,
						code: key
					});
				});

				this.countries = countryList.sort(this.selectSort);
			}, (response) => {
				console.log("Error loading countries");
			});
        },
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                totalFreelancers: 0,
                availableSkills: [],
                countries: [],
                cities: [],
                errors: {},
				skillOptions: {
					pager: {
						page: 1,
						size: 25
					},
					search: '',
					filter: '',
					paginate: false
				},
                options: {
                    freelancer_id: null,
                    skills: [],
                    country: '',
                    city: '',
                    pager: {
                        page: 1,
                        size: 100
                    },
                    search: '',
                    filter: ''
                },
                state: {
                    searchSelected: true,
                    selectedFreelancer: {},
					selectedSkill : '',
                    loading: true,
                    showAgencies: true
                }
            }
        },
        computed: {
            isFiltered: function () {
                return this.options.search || this.options.filter;
            },
            cityPlaceholder: function() {
                if (this.options.country) {
                	return 'Start typing...'
                } else {
                	return 'Select a country first...'
                }
            },
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
			autoCompleteMatch: function (suggestion, query) {
				return suggestion.value.toLowerCase().indexOf(query.toLowerCase()) !== -1;
			},
        	isMatchedSkill: function(skill) {
        	    let match = this.options.skills.find((selectedSkill) => {
        	    	return selectedSkill.id == skill.id;
                });

        	    return match;
            },
			selectSort: function (a, b) {
				if (a.name < b.name) {
					return -1;
				}

				if (a.name > b.name) {
					return 1;
				}

				return 0;
			},
            onFilter(filter) {
                this.options.filter = filter;
                this.refreshFreelancers();
            },
            clearFilters() {
                this.options.search = '';
                this.options.filter = '';
                this.options.skills = [];
                this.options.country = "";
                this.options.city = "";

                this.refreshFreelancers();
            },
            removeSkillFilter(index) {
        		console.log('removing skill filter');
        		this.options.skills.splice(index, 1);
            },
			updateCitySelect() {
				let country = null;
				let vm = this;
				country = _.find(this.countries, function (country) {
					return country.name == vm.options.country;
				});

				if (!country) {
					return;
                }

                /* Load countries and cities */
				this.$http.get('/apiv1/cities', {
					params: {
						countryCode: country.code,
						country: country.name,
						format: 'autocomplete'
					}
				}).then((response) => {
					this.cities = response.body;
				}, (response) => {
					console.log("Error loading cities");
				});
			},
			addSelectedSkill(selected) {
            	var activeSkill = false;
            	let vm = this;
				_.each(this.shared.skills, function (skill) {
					if (skill.name == vm.state.selectedSkill) {
						activeSkill = skill;
					}
				});
				if (activeSkill) {
					this.options.skills.push({

						id: activeSkill.id,
                        name: activeSkill.name,
                        level: 'any'
                    });
                }

                this.state.selectedSkill = '';

			},
            onSearch(term) {
                this.options.search = term;
                //this.refreshFreelancers();
            },
			inviteFreelancerAgency(freelancer) {
				appBus.$emit('agency:inviteFreelancer', freelancer);
			},
            inviteFreelancer(freelancer) {
				appBus.$emit('openInvite', freelancer, false);
            },
			inviteAgency(agency) {
				appBus.$emit('openInvite', agency, true);
			},
            viewFreelancer(freelancer) {
                window.location = this.freelancerLink(freelancer);
            },
			viewAgency(agency) {
				window.location = `/agency/${agency.id}`;
			},
            editFreelancer(freelancer) {
                window.location = this.freelancerEditLink(freelancer);
            },
            isFreelancerEditable(freelancer) {
                if (freelancer.owner_id == this.shared.user.id) {
                    return true;
                }

                return false;

            },
            freelancerLink: function (freelancer) {
                return "/profile/" + freelancer.id;
            },
            freelancerEditLink: function (freelancer) {
                return "/freelancers/edit/" + freelancer.id;
            },
            refreshFreelancers()
            {
                this.state.loading = true;
                this.$http.get('/apiv1/freelancers', {params: this.options}).then((response) => {
                    console.log(response);
                    this.shared.freelancers = response.body.data;
                    this.totalFreelancers = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log("Error loading freelancers.");
                    console.log(response);
                });
            },
			refreshSkills()
			{
				this.$http.get('/apiv1/skills', {params: this.skillOptions}).then((response) => {
						this.shared.skills = response.body;

						var vm = this;
						vm.availableSkills = [];
						_.each(this.shared.skills, function (skill) {

							var suffix = '';
							if (skill.users_count != 1) {
								suffix = 's';
							}

							vm.availableSkills.push({
								//'label' : skill.name + ' (' + skill.users_count + ' Freelancer' + suffix +')',
								'label': skill.name,
								'value': skill.name
							});
						})

					}, (response) => {
						this.$root.showNotification(response.body.message);
						console.log("Error loading skills");
						console.log(response);
					}
				);
			},
        }
    }
</script>
