<template>
<div>
    <md-table-card>
        <md-toolbar>
            <h1 class="md-title">Agencies</h1>
            <md-menu md-size="4">
                <md-button class="md-icon-button" md-menu-trigger>
                    <md-icon>filter_list</md-icon>
                </md-button>

                <md-menu-content>
                    <md-menu-item @selected="onFilter" >
                        <span id="filter-members">More Than One Member</span>
                    </md-menu-item>

                    <md-menu-item @selected="onFilter" >
                        <span id="filter-no-members">No Members Yet (Owner Only)</span>
                    </md-menu-item>
                    
                    <md-menu-item>
                        <span>More Later...</span>
                    </md-menu-item>

                </md-menu-content>
            </md-menu>


            <md-button class="md-icon-button" @click="refreshTable()">
                <md-icon>cached</md-icon>
            </md-button>

            <md-button v-if="isFiltered" class="md-icon-button" @click="clearFilters">
                <md-icon>clear</md-icon>
            </md-button>

            <div v-if="state.searchSelected" class="col-xs-12">
                <md-input-container md-inline>
                    <label>Search Agencies</label>
                    <md-input v-model="options.search" @change="onSearch"></md-input>
                    <md-button  class="md-input-button" @click="state.searchSelected = !state.searchSelected">
                        <md-icon>search</md-icon>
                    </md-button>
                </md-input-container>
            </div>

            <md-button v-if="!state.searchSelected" class="md-icon-button" @click="state.searchSelected = !state.searchSelected">
                <md-icon>search</md-icon>
            </md-button>



        </md-toolbar>

        <md-table-alternate-header md-selected-label="selected">
            <md-button class="md-icon-button">
                <md-icon>delete</md-icon>
            </md-button>
        </md-table-alternate-header>

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

        <md-table v-else  :md-sort="options.sort.name" :md-sort-type="options.sort.type" @sort="onSort" @select="onSelected">
            <md-table-header>
                <md-table-row>
                    <md-table-head md-sort-by="name">Name</md-table-head>
                    <md-table-head md-sort-by="owner">Owner</md-table-head>
                    <md-table-head md-sort-by="location">Location</md-table-head>
                    <md-table-head md-sort-by="members">Members</md-table-head>
                    <md-table-head md-sort-by="status">Status</md-table-head>
                    <md-table-head md-sort-by="created_at">Created At</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="(agency, rowIndex) in agencies" :key="rowIndex" :md-item="agency">
                    <md-table-cell>
                        <span>
                            {{ agency.name|limitTo(24) }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <a :href="profileLink(agency.owner)" class="link-primary" target="_blank">{{ userFormat(agency.owner) }}</a>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ agency.location }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span class="label label-primary">
                            {{ agency.freelancers.length + 1 }} Member(s)
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span  class="label label-primary pading-10-5">{{ agency.status|capitalize }}</span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ agency.created_at }}
                        </span>

                        <md-button target="blank" :href="agencyLink(agency)" class="md-icon-button">
                        <md-icon>assignment</md-icon>
                        </md-button>

                        <md-button class="md-icon-button" @click="confirmDelete(agency)">
                            <md-icon>delete</md-icon>
                        </md-button>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>
        </md-table >
        <md-table-pagination
                :md-size="options.pager.size"
                :md-total="totalAgencies"
                :md-page="options.pager.page"
                md-label="Agencies"
                md-separator="of"
                :md-page-options="[20, 50, 100]"
                @pagination="onPagination"></md-table-pagination>

        <div class="margin-top-40">&nbsp;</div>
    </md-table-card>

    <md-dialog-confirm
            md-title="Delete this agency?"
            md-content-html="This action can not be reversed and the agency data will be permenantly deleted."
            md-ok-text="Delete Agency"
            md-cancel-text="Cancel"
            @close="deleteAgency"
            ref="delete-agency">
    </md-dialog-confirm>


</div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Admin agencies component ready.')
            this.refreshTable();
        },
        data: () => ({
            state: {
                searchSelected : false,
                loading: false,
                selectedAgency : null,
            },
            columns: ['name','owner','location','members','status','created_at'],
            defaultColumns: ['name','created_at'],
            labelColumns: ['status','members'],
            userColumns: ['owner'],
            options: {
                pager: {
                    page: 1,
                    size: 20
                },
                sort : {
                    name: 'created_at',
                    type: 'desc'
                },
                search: '',
                filter: null
            },
            totalAgencies: 5000,
            user: window.Laravel.user,
            shared: window.sageSource,
            searchTerm: null,
            agencies: [],
            notifyPosition: 'right',
            allowHtmlNotifications: true,
            queueNotifications: true,
        }),
        computed: {
            isFiltered : function() {
                return this.options.search || this.options.filter;
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
            userFormat(user) {
                if (user) {
                    return user.name;
                }

                return "N/A";
            },
            agencyLink: function (agency) {
                return "/agency/" + agency.id;
            },
            profileLink: function (profile) {
                return "/profile/" + profile.id;
            },
            clearFilters() {
              this.options.search = '';
              this.options.filter = '';
              this.state.searchSelected = false;
              this.refreshTable();
            },
            onPagination(pageOptions) {
                this.options.pager = pageOptions;
                this.refreshTable();
            },
            onSort(sortOptions) {
              this.options.sort = sortOptions;
                this.refreshTable();
            },
            onSelected(selectedRows) {
                this.options.selected = selectedRows;
            },
            onFilter(filter) {
                this.options.filter = filter.target.id;
                this.refreshTable();
            },
            onSearch(term) {
                this.options.search = term;
                this.refreshTable();
            },
            refreshTable()
            {
                this.state.loading = true;
                this.options.page = this.options.pager.page;
                this.$http.get('/apiv1/agencies', {params : this.options}).then((response) => {

                    this.agencies = response.body.data;
                    this.options.pager.page = response.body['current_page'];
                    this.totalAgencies = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    console.log("Error loading agencies.");
                    console.log(response);
                });
            },
            confirmDelete(agency) {
                this.state.selectedAgency = agency;
                this.$refs['delete-agency'].open();
            },
            deleteAgency(confirmation) {
              if (confirmation !== 'ok') {
                  return;
              }

              if (!this.state.selectedAgency || !this.state.selectedAgency.id) {
                  this.$root.showNotification('Invalid agency selected, please refresh the agencies and try again.');
              }

                this.$http.post('/apiv1/agencies/delete/' + this.state.selectedAgency.id, this.state.selectedAgency).then((response) => {

                    this.$root.showNotification(response.body.message);
                    this.refreshTable();

                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log(response);
                });

              this.selectedAgency = null;

            },
            showNotification(message) {
                this.$root.showNotification(message);
            },
            openRootDialog(ref) {
                this.$root.openDialog(ref);
            },
            openDialog(ref) {
                this.$refs[ref].open();
            }

        }
    }
</script>