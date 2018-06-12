<template>
    <div>
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

        <div v-if="shared.profile.agencies.length === 0 && shared.profile.owned_agencies.length === 0">
            <md-list class="custom-list md-dense">
                <md-list-item>
                    <md-icon class="font-size-30">
                        announcement
                    </md-icon>

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <p>{{ shared.profile.name}} does not belong to any agencies.</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <md-list class="custom-list md-triple-line" v-if="!state.loading">
            <md-list-item v-for="(agency, key, index) in shared.profile.owned_agencies" :key="index">
                <md-icon class="font-size-40">
                    card_membership
                </md-icon>

                <div class="md-list-text-container margin-top-10 padding-bottom-5">
                    <div>
                        <span>
                            <span
                                    class="label label-primary pull-right margin-right-20 no-margin-xs">Agency Owner</span>
                            <a :href="agencyLink(agency)" class="link-primary">{{ agency.name }}</a></span>
                    </div>
                    <span>{{ agency.freelancers.length + 1}} Member(s)</span>
                    <p>{{ agency.short_description  }}</p>
                </div>

                <md-divider class="md-inset"></md-divider>
            </md-list-item>
            <md-list-item v-for="(agency, key, index) in shared.profile.agencies" :key="index">
                <md-icon class="font-size-40">
                    card_membership
                </md-icon>

                <div class="md-list-text-container margin-top-10 padding-bottom-5">
                    <div>
                        <span>
                            <span
                                    class="label label-primary pull-right margin-right-20 no-margin-xs">Agency Member</span>
                            <a :href="agencyLink(agency)" class="link-primary">{{ agency.name }}</a></span>
                    </div>
                    <span>{{ agency.freelancers.length + 1}} Member(s)</span>
                    <p>{{ agency.short_description | limitTo(140) }}</p>
                </div>
                <md-divider class="md-inset"></md-divider>
            </md-list-item>


        </md-list>
        <div class="clearfix"></div>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log('Profile agencies component ready.')
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
        computed: {},
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
            isAgencyEditable(agency) {

                if (agency.owner_id == this.user.id) {
                    return true;
                }

                return false;

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
