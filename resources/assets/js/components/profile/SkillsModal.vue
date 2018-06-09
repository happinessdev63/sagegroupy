<template>
    <!-- Freelancer Search Modal -->
    <ui-modal ref="userSkillsModal" transition="ui-modal-fade">
        <div slot="header">
            {{ user.name }} Skills Details
        </div>

        <div class="min-height-500">
            <div class="clearfix"></div>
            <div v-if="user.skills.length == 0 && !state.loading">
                <div class="text-center well">
                    <h5 class="font-size-16">
                        <md-icon class="md-primary font-size-40 margin-bottom-30">info</md-icon>
                        <br/>
                        This user does not have any skills added yet!
                    </h5>
                </div>
            </div>

            <div v-if="user.skills.length > 0 && !state.loading">
                <table class="table table-striped  table-condensed">
                    <thead>
                        <th class="text-left">Name</th>
                        <th class="text-center">Skill Level</th>
                        <th class="text-center">Experience</th>
                        <th class="text-center">Rate</th>
                    </thead>
                    <tbody>
                        <tr v-for="(skill, index) in user.skills">
                            <td class="text-left">
                                {{ skill.name }}
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
        </div>

        </div>

        <div slot="footer">
            <md-button class="md-primary" @click="closeModal('userSkillsModal')">Close</md-button>
        </div>
    </ui-modal>

</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log(' Skills modal component ready.');
            var vm = this;
            appBus.$on('showUserSkills', function () {
                vm.$refs['userSkillsModal'].open();
            });

        },
        created() {
        },
        props: ['user'],
        data() {
            return {
                shared: window.sageSource,
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
                    loading: false,
                }
            }
        },
        computed: {

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