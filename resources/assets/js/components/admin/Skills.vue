<template>
<div>
    <md-table-card>
        <md-toolbar>
            <h1 class="md-title">Skills</h1>
            <md-menu md-size="4">
                <md-button class="md-icon-button" md-menu-trigger>
                    <md-icon>filter_list</md-icon>
                </md-button>

                <md-menu-content>
                    <md-menu-item @selected="onFilter" >
                        <span id="filter-members">More Than One Freelancer</span>
                    </md-menu-item>

                    <md-menu-item @selected="onFilter" >
                        <span id="filter-no-members">No Freelancers</span>
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
                    <label>Search Skills</label>
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
                    <md-table-head md-sort-by="owner">Keywords</md-table-head>
                    <md-table-head md-sort-by="users_count">Number of Freelancers</md-table-head>
                    <md-table-head>Avg Rates</md-table-head>
                    <md-table-head md-sort-by="created_at">Created At</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="(skill, rowIndex) in shared.skills" :key="rowIndex" :md-item="skill">
                    <md-table-cell>
                        <span>
                            {{ skill.name }}
                        </span>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ skill.keywords }}
                        </span>
                    </md-table-cell>                    
                    <md-table-cell>
                        <div>
                            <md-button class="md-primary" style="min-width: 110px;" @click.native="toggleUserDisplay(skill)">
                                {{ skill.users_count }} User(s)
                                <md-icon v-if="state.userDisplayToggle != skill.id">keyboard_arrow_down</md-icon>
                                <md-icon v-else>keyboard_arrow_up</md-icon>
                            </md-button>
                            <ul class="list-group" v-if="state.userDisplayToggle == skill.id" style="width: 100%;">
                                <li class="list-group-item" v-for="(user,userIndex) in skill.users"><a class="link-primary" target='_blank':href="profileLink(user)">{{ user.name }}</a></li>
                            </ul>
                        </div>

                    </md-table-cell>
                    <md-table-cell style="min-width: 200px;">
                        <div>
                            <md-button class="md-primary font-size-12" style="min-width: 110px;" @click.native="toggleRateDisplay(skill)">
                                ${{ avgRate(skill, 'Total') }}
                                <md-icon v-if="state.rateDisplayToggle != skill.id">keyboard_arrow_down</md-icon>
                                <md-icon v-else>keyboard_arrow_up</md-icon>
                            </md-button>
                            <ul class="list-group" v-if="state.rateDisplayToggle == skill.id" style="width: 100%;">
                                <li class="list-group-item" v-for="(rate, rateIndex) in skill.avg_rates">
                                    <strong>{{ rate.level }}</strong>  <br/> ${{ rate.rate }}/hr
                                </li>
                            </ul>
                        </div>
                    </md-table-cell>
                    <md-table-cell>
                        <span>
                            {{ skill.created_at }}
                        </span>
                        <md-button class="md-icon-button" @click="confirmDeleteSkill(skill)">
                            <md-icon>delete</md-icon>
                        </md-button>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>
        </md-table >
        <md-table-pagination
                :md-size="options.pager.size"
                :md-total="totalSkills"
                :md-page="options.pager.page"
                md-label="Skills"
                md-separator="of"
                :md-page-options="[20, 50, 100]"
                @pagination="onPagination"></md-table-pagination>

        <div class="margin-top-40">&nbsp;</div>
    </md-table-card>
    
    <md-dialog-confirm
            md-title="Delete this skill?"
            md-content-html="This action can not be reversed and the skill along with its data will be permenantly deleted."
            md-ok-text="Delete Skill"
            md-cancel-text="Cancel"
            @close="deleteSkill"
            ref="delete-skill">
    </md-dialog-confirm>


</div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Admin skills component ready.')
            this.refreshTable();
        },
        data: () => ({
            state: {
                searchSelected : false,
                loading: false,
                selectedSkill : null,
                userDisplayToggle: false,
                rateDisplayToggle: false
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
                    name: 'users_count',
                    type: 'desc'
                },
                search: '',
                includeUsers: true,
                filter: null
            },
            totalSkills: 5000,
            user: window.Laravel.user,
            shared: window.sageSource,
            searchTerm: null,
            skills: [],
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
            avgRate(skill, level) {
                var avgRate = '0.00';
                _.each(skill.avg_rates, function(rate) {
                    if (rate.level == level) {
                        avgRate = rate.rate;
                    }
                });

                return avgRate;
            },
            toggleUserDisplay(skill) {
                if (this.state.userDisplayToggle == false) {
                    this.state.userDisplayToggle = skill.id;
                } else {
                    this.state.userDisplayToggle = false;
                }
            },
            toggleRateDisplay(skill) {
                if (this.state.rateDisplayToggle == false) {
                    this.state.rateDisplayToggle = skill.id;
                } else {
                    this.state.rateDisplayToggle = false;
                }
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
                this.$http.get('/apiv1/skills', {params : this.options}).then((response) => {

                    this.shared.skills = response.body.data;
                    this.options.pager.page = response.body['current_page'];
                    this.totalSkills = response.body.total;
                    this.state.loading = false;

                }, (response) => {
                    console.log("Error loading skills.");
                    console.log(response);
                });
            },
            confirmDeleteSkill(skill) {
                this.shared.skill = skill;
                this.$refs['delete-skill'].open();
            },
            deleteSkill(confirmation) {

                if (confirmation !== 'ok') {
                    return;
                }

                var apiUrl = '/apiv1/skills/delete/' + this.shared.skill.id;
                this.$http.post(apiUrl).then((response) => {
                        this.$root.showNotification(response.body.message);
                        this.refreshTable();
                    },
                    (response) => {
                        this.$root.showNotification(response.body.message);
                    });

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