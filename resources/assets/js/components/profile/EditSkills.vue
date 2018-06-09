<template>
    <div class="min-height-300">
        <div>
            <md-button class="md-primary pull-right" @click.native="state.showAddSkill = !state.showAddSkill">
                <md-icon v-if="state.showAddSkill">keyboard_arrow_up</md-icon>
                <md-icon v-else>add</md-icon>
                Add Skill
            </md-button>
        </div>
        <div class="clearfix"></div>
        <div class="well" v-if="state.showAddSkill">
            <div class="col-md-10">
            <ui-autocomplete
                    :autofocus="true"
                    :limit="8"
                    :filter="autoCompleteMatch"
                    label="Select a Skill to Add"
                    name="new_skill"
                    placeholder="Start typing..."
                    :suggestions="availableSkills"
                    :minChars="1"
                    v-model="state.selectedSkill"
                    @blur="updateSelectedSkill"
            ></ui-autocomplete>
            </div>
            <div class="col-md-2">
            <md-button v-if='state.selectedSkill' class="md-primary margin-top-20 pull-right" @click.native="openParentModal('saveSkillModal')">
                <md-icon>done</md-icon>
                Save
            </md-button>
                <md-button v-else='state.selectedSkill' disabled class="md-primary margin-top-20 pull-right">
                    <md-icon>done</md-icon>
                    Save
                </md-button>
            </div>
            <div class="clearfix"></div>

        </div>
        <div v-if="shared.profile.skills.length == 0 && !state.loading">
            <div class="text-center well">
                <h5 class="font-size-16">
                    <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                    <br/>
                    You have not added any skills!
                    <br/><br/> For maximum visibility, add up to 12 skills to your profile.
                </h5>
            </div>
        </div>

        <div v-if="shared.profile.skills.length > 0 && !state.loading">
            <table class="table table-striped  table-condensed">
                <thead>
                    <th class="text-left">Name</th>
                    <th class="text-left">Suggestions</th>
                    <th class="text-center">Skill Level</th>
                    <th class="text-center">Experience</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Options</th>
                </thead>
                <tbody>
                    <tr v-for="(skill, index) in shared.profile.skills">
                        <td class="text-left">
                            {{ skill.name }}
                        </td>
                        <td class="text-left">
                            <div v-if="skill.suggestions.length > 0">
                                <md-button class="md-primary font-size-12" @click.native="toggleSuggestionDisplay(skill)">
                                    {{ skill.suggestions.length }} Suggestion(s)
                                    <md-icon v-if="state.suggestionDisplayToggle != skill.id">keyboard_arrow_down</md-icon>
                                    <md-icon v-else>keyboard_arrow_up</md-icon>
                                </md-button>
                                <ul class="list-group" v-if="state.suggestionDisplayToggle == skill.id" style="width: 100%;">
                                    <li class="list-group-item" v-for="(suggestion, suggestionIndex) in skill.suggestions">
                                        {{ suggestion }}
                                    </li>
                                </ul>
                            </div>
                            <md-button v-else class="md-primary  font-size-12">No Suggestions</md-button>
                        </td>
                        <td class="text-center">
                            {{ skill.pivot.level }}
                        </td>
                        <td class="text-center">
                            {{ skill.pivot.experience }} Year(s)
                        </td>
                        <td class="text-center">
                            ${{ skill.pivot.rate }}/hr
                        </td>
                        <td class="text-center">
                            <md-button class="md-icon-button md-primary" @click.native="editSkill(skill)">
                                <md-icon>edit</md-icon>
                            </md-button>
                            <md-button class="md-icon-button md-primary" @click.native="confirmDeleteSkill(skill)">
                                <md-icon>delete</md-icon>
                            </md-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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
            console.log(' Profile skills component ready.');
            this.refreshSkills();

            var vm = this;
            appBus.$on('saveSkill', function (skill) {
                console.log(vm.shared.skill);
                //vm.saveSkill();
            });

            appBus.$on('savedSkill', function (skill) {
            	vm.state.selectedSkill = '';
                vm.refreshUser();
            });
        },
        created() {
        },
        props: [],
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                availableSkills : [],
                errors: {},
                options: {
                    'pager': {
                        page: 1,
                        size: 25
                    },
                    search: '',
                    filter: '',
                    paginate: false
                },
                state: {
                    showCompletedSkills: false,
                    selectedSkill: '',
                    loading: false,
                    showAddSkill: false,
                    suggestionDisplayToggle: false
                }
            }
        },
        computed: {

            activeSkill: function() {
                if (!this.state.selectedSkill) {
                    this.shared.defaultSkill;
                }

                var vm = this;
                var activeSkill = this.shared.defaultSkill;
                activeSkill.name = this.state.selectedSkill;
                _.each(this.shared.skills, function(skill) {
                    if (skill.name == vm.state.selectedSkill) {
                        activeSkill = skill;
                    }
                });
                return activeSkill;
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
			autoCompleteMatch: function (suggestion, query) {
				return suggestion.value.toLowerCase().indexOf(query.toLowerCase()) !== -1;
			},
            toggleSuggestionDisplay(skill) {
                if (this.state.suggestionDisplayToggle == false) {
                    this.state.suggestionDisplayToggle = skill.id;
                } else {
                    this.state.suggestionDisplayToggle = false;
                }
            },
            updateSelectedSkill(selected) {
                this.shared.skill = this.activeSkill;

                /* Set the skill rate/experience/level to that of the last skill entered for this user */
                if (this.shared.profile.skills && this.shared.profile.skills.length > 0) {
                	let lastSkill = this.shared.profile.skills[this.shared.profile.skills.length - 1];
                	console.log(lastSkill);
                	console.log("Last Skill for User");
					this.shared.skill.rate = lastSkill.pivot.rate;
					this.shared.skill.experience = lastSkill.pivot.experience;
					this.shared.skill.level = lastSkill.pivot.level;
                }
            },
            confirmDeleteSkill(skill) {
                this.shared.skill = skill;
                this.$refs['delete-skill'].open();
            },
            editSkill(skill) {
              this.shared.skill = skill;
              this.shared.skill.rate = skill.pivot.rate;
              this.shared.skill.experience = skill.pivot.experience;
              this.shared.skill.level = skill.pivot.level;
              this.openParentModal('saveSkillModal');
            },
            deleteSkill(confirmation) {

                if (confirmation !== 'ok') {
                    return;
                }

                var apiUrl = '/apiv1/skills/removeUserSkill/' + this.shared.skill.id;
                this.$http.post(apiUrl).then((response) => {
                    this.$root.showNotification(response.body.message);
                    this.refreshSkills();
                    this.refreshUser();
                },
                (response) => {
                    this.$root.showNotification(response.body.message);
                });

            },
            refreshUser() {
                this.state.loading = true;
                this.$http.get('/apiv1/users/' + this.shared.profile.id).then((response) => {
                    this.shared.profile = response.body.user;
                    this.state.loading = false;
                },(response) =>
                    {
                        this.$root.showNotification(response.body.message);
                    }
                );
            },
            refreshSkills()
            {
                this.state.loading = true;
                this.$http.get('/apiv1/skills', {params: this.options}).then((response) => {
                    this.shared.skills = response.body;

                    var vm = this;
                    vm.availableSkills = [];
                    _.each(this.shared.skills, function(skill) {

                        var suffix = '';
                        if (skill.users_count != 1) {
                            suffix = 's';
                        }

                        vm.availableSkills.push({
                            //'label' : skill.name + ' (' + skill.users_count + ' Freelancer' + suffix +')',
                            'label' : skill.name,
                            'value' : skill.name
                        });
                    })
                    this.state.loading = false;

                },(response) =>
                    {
                        this.$root.showNotification(response.body.message);
                        console.log("Error loading skills");
                        console.log(response);
                    }
                );
            },
            openParentModal(ref) {
                appBus.$emit('openParentModal', ref);
            },
            closeParentModal(ref) {
                appBus.$emit('closeParentModal', ref);
            },
            openModal(ref) {
                this.$refs[ref].open();
            },
            closeModal(ref) {
                this.$refs[ref].close();
            },
        }
    }

</script>