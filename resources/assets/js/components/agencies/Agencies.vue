<template>
    <div>
        <h5 class="text-muted pull-left">Found {{ shared.agencies.length }} Agencies</h5>
        <div class="pull-right">

            <md-button class="md-icon-button md-primary" @click="refreshAgencies()">
                <md-icon>cached</md-icon>
            </md-button>

            <md-button v-if="!state.searchSelected" class="md-icon-button md-primary" @click="state.searchSelected = !state.searchSelected">
                <md-icon>search</md-icon>
            </md-button>


            <md-button v-if="isFiltered || state.SearchSelected" class="md-primary" @click="clearFilters">
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

                    <md-menu-item @click="onFilter('filter-own-agencies')">
                        <span id="filter-own-agencies">Your Agencies</span>
                    </md-menu-item>

                    <md-menu-item @click="onFilter('filter-eligible-agencies')">
                        <span id="filter-eligible-agencies">Eligible Agencies</span>
                    </md-menu-item>

                </md-menu-content>
            </md-menu>


        </div>

        <div class="clearfix"></div>

        <div v-if="state.searchSelected" class="col-xs-12">
            <md-input-container md-inline>
                <label>Search Agencies</label>
                <md-input v-model="options.search" @change="onSearch"></md-input>
                <md-button class="md-input-button md-primary" @click="state.searchSelected = !state.searchSelected">
                    <md-icon>search</md-icon>
                </md-button>
            </md-input-container>
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


        <div v-if="totalAgencies == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    There are no available agencies yet.
                    <br/><br/> You can create your own agency and invite other freelancers to join your agency.
                </h5>

            </div>
        </div>

        <md-list class="custom-list md-triple-line margin-top-20" v-if="!state.loading">
            <div v-if="!state.loading">
                <md-list-item v-for="(agency, key, index) in shared.agencies" :key="index">
                    <img :src="agency.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;"
                         :alt="agency.owner.name">

                    <div class="md-list-text-container margin-top-10 padding-bottom-5 margin-left-10">
                        <div>
                            <span>
                                <span class="label label-primary pull-right margin-right-20 no-margin-xs">
                                      {{ agency.freelancers.length + 1}} Member(s)
                                </span>
                                <a :href="agencyLink(agency)" class="link-primary">{{ agency.name }}</a></span>
                        </div>
                        <span>{{ agency.location }}</span>
                        <p>{{ agency.short_description }}</p>
                    </div>

                    <md-menu md-size="4">
                        <md-button class="md-icon-button md-list-action" md-menu-trigger>
                            <md-icon clas="md-primary">more_vert</md-icon>
                        </md-button>

                        <md-menu-content>

                            <sage-join-agency :agency-id="agency.id" type="menu-item"></sage-join-agency>

                            <md-menu-item @click="viewAgency(agency)">
                                <md-icon clas="md-primary">card_membership</md-icon>
                                <span>View Agency</span>
                            </md-menu-item>

                        </md-menu-content>
                    </md-menu>

                    <md-divider class="md-inset-110"></md-divider>
                </md-list-item>
            </div>

        </md-list>
        <div class="clearfix"></div>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Agency listings component ready.')
            var vm = this;
            this.refreshAgencies();
        },
        created() {

        },
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                totalAgencies: 0,
                errors: {},
                options: {
                    'agency_id': null,
                    'pager': {
                        page: 1,
                        size: 25
                    },
                    search: '',
                    filter: null
                },
                state: {
                    searchSelected: false,
                    selectedAgency: {},
                    loading: true
                }
            }
        },
        computed: {
            isFiltered: function () {
                return this.options.search || this.options.filter;
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
            onFilter(filter) {
                this.options.filter = filter;
                this.refreshAgencies();
            },
            clearFilters() {
                this.options.search = '';
                this.options.filter = '';
                this.refreshAgencies();
            },
            onSearch(term) {
                this.options.search = term;
                this.refreshAgencies();
            },
            viewAgency(agency) {
                window.location = this.agencyLink(agency);
            },
            editAgency(agency) {
                window.location = this.agencyEditLink(agency);
            },
            isAgencyEditable(agency) {
                if (agency.owner_id == this.shared.user.id) {
                    return true;
                }

                return false;

            },
            agencyLink: function (agency) {
                return "/agency/" + agency.id;
            },
            agencyEditLink: function (agency) {
                return "/agencies/edit/" + agency.id;
            },
            refreshAgencies()
            {
                this.state.loading = true;
                this.$http.get('/apiv1/agencies', {params: this.options}).then((response) => {
                    this.shared.agencies = response.body.data;
                    this.totalAgencies = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log("Error loading agencies.");
                    console.log(response);
                });
            }
        }
    }
</script>
