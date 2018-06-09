<template>
<div>

    <md-whiteframe class="bg-white">
        <md-tabs md-fixed class="md-transparent margin-top-20">

            <md-tab id="general_settings" md-label="General Setttings">

                <h4>No General Settings Yet</h4>
                <div class="clearfix"></div>
                <md-button class="md-primary pull-right">
                    <md-icon>save</md-icon>
                    Save
                </md-button>
            </md-tab>

            <md-tab id="skill_settings" md-label="Skills Settings">

                <div class="col-md-6">

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.max_skills }">
                        <label>Max Number of Skills</label>
                        <md-input type="number" v-model="shared.settings.max_skills"></md-input>
                        <span v-if="errors.max_skills" class="md-error">{{ errors.max_skills[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.high_rate_threshold }">
                        <label>High Rate Warning Threshold</label>
                        <md-input type="number"
                                  v-model="shared.settings.high_rate_threshold"
                                  placeholder="Enter a rate between 1 and 10.">
                        </md-input>
                        <span v-if="errors.high_rate_threshold" class="md-error">{{ errors.high_rate_threshold[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.low_rate_threshold }">
                        <label>Low Rate Warning Threshold</label>
                        <md-input type="number"
                                  v-model="shared.settings.low_rate_threshold"
                                  placeholder="Enter a rate between 0 and 1.">
                        </md-input>
                        <span v-if="errors.low_rate_threshold" class="md-error">{{ errors.low_rate_threshold[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.low_user_count_threshold }">
                        <label>Low User Count Threshold</label>
                        <md-input type="number"
                                  v-model="shared.settings.low_user_count_threshold"
                                  placeholder="Enter a number between 0 and 50.">
                        </md-input>
                        <span v-if="errors.low_user_count_threshold" class="md-error">{{ errors.low_user_count_threshold[0] }}</span>
                    </md-input-container>
                </div>

                <div class="col-md-6">

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.suggestion_no_users }">
                        <label>Suggestion when no other users have the same skill:</label>
                        <md-input type="text" v-model="shared.settings.suggestion_no_users"></md-input>
                        <span v-if="errors.suggestion_no_users" class="md-error">{{ errors.suggestion_no_users[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.suggestion_high_rate }">
                        <label>Suggestion when rate entered is {{ shared.settings.high_rate_threshold }} times higher than avg:</label>
                        <md-input type="text" v-model="shared.settings.suggestion_high_rate"></md-input>
                        <span v-if="errors.suggestion_high_rate" class="md-error">{{ errors.suggestion_high_rate[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.suggestion_low_rate }">
                        <label>Suggestion when rate entered is {{ shared.settings.low_rate_threshold }} times lower than avg:</label>
                        <md-input type="text" v-model="shared.settings.suggestion_low_rate"></md-input>
                        <span v-if="errors.suggestion_low_rate" class="md-error">{{ errors.suggestion_low_rate[0] }}</span>
                    </md-input-container>

                    <md-input-container v-bind:class="{ 'md-input-invalid' : errors.suggestion_low_user_count }">
                        <label>Suggestion when less than {{ shared.settings.low_user_count_threshold }} users have the same skill:</label>
                        <md-input type="text" v-model="shared.settings.suggestion_low_user_count"></md-input>
                        <span v-if="errors.suggestion_low_user_count" class="md-error">{{ errors.suggestion_low_user_count[0] }}</span>
                    </md-input-container>
                </div>


                <div class="clearfix"></div>
                <md-button class="md-primary pull-right" @click="saveSettings('skills')">
                    <md-icon>save</md-icon>
                    Save
                </md-button>
            </md-tab>

            <md-tab id="search_settings" md-label="Search Settings">
                <div class="col-md-6">

                    <md-input-container>
                        <label>Weight For Number of Skills:</label>
                        <md-select v-model="shared.settings.search_skills_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>

                    <md-input-container>
                        <label>Weight For Having Avatar Updated:</label>
                        <md-select v-model="shared.settings.search_avatar_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>


                    <md-input-container>
                        <label>Weight For Number of Agencies User Belongs To:</label>
                        <md-select v-model="shared.settings.search_agencies_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>

                    <md-input-container>
                        <label>Weight For Number of Jobs a User Has:</label>
                        <md-select v-model="shared.settings.search_jobs_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>

                    <md-input-container>
                        <label>Weight For Number of Completed Jobs a User Has:</label>
                        <md-select v-model="shared.settings.search_completd_jobs_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>

                    <md-input-container>
                        <label>Weight For Average Rating:</label>
                        <md-select v-model="shared.settings.search_rating_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>

                    <md-input-container>
                        <label>Weight For Completed Profile:</label>
                        <md-select v-model="shared.settings.search_profile_weight">
                            <md-option value="0">0</md-option>
                            <md-option value="1">1</md-option>
                            <md-option value="2">2</md-option>
                            <md-option value="3">3</md-option>
                            <md-option value="4">4</md-option>
                            <md-option value="5">5</md-option>
                            <md-option value="6">6</md-option>
                            <md-option value="7">7</md-option>
                            <md-option value="8">8</md-option>
                            <md-option value="9">9</md-option>
                            <md-option value="10">10</md-option>
                        </md-select>
                    </md-input-container>
                </div>
                <div class="clearfix"></div>
                <md-button class="md-primary pull-right" @click="saveSettings('search')">
                    <md-icon>save</md-icon>
                    Save
                </md-button>
            </md-tab>



        </md-tabs>
        <div class="clearfix"></div>
    </md-whiteframe>


</div>
</template>

<script type="text/babel">

    export default {
        mounted() {
            console.log(' Admin settings component ready.')

        },
        data: () => ({
            state: {
                loading: false,
            },
            errors: {},
            user: window.Laravel.user,
            shared: window.sageSource,
        }),
        computed: {

        },
        filters: {

        },
        methods: {
            updateEditor(html) {
              this.html = html;
            },
            saveSettings(type) {
                var apiUrl = '/apiv1/settings/update/' + type;
                this.$http.post(apiUrl, this.shared.settings).then((response) => {
                        this.$root.showNotification(response.body.message);
                        this.errors = {};
                    },
                    (response) => {
                        this.errors = response.body;
                        this.$root.showNotification("There was an error saving the settings, please try again.");
                    });
            },
            showNotification(message) {
                this.$root.showNotification(message);
            }
        }
    }
</script>