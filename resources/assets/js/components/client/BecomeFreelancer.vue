<template>
    <div v-if="user.is_client && !user.is_freelancer">
        <md-button class="md-primary md-raised btn-block no-margin-right no-margin-left" @click="confirmBecomeFreelancer">Become a Freelancer</md-button>
        <md-dialog-confirm
                md-title="Become a Freelancer"
                md-content-html="After confirming your profile will be viewable in the freelancer database.
                <br/>
                You will still have all the Client features available as well. Are you sure you would like to continue?"
                md-ok-text="Confirm"
                md-cancel-text="Cancel"
                @close="becomeFreelancer"
                ref="become-freelancer">
        </md-dialog-confirm>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log(' Become freelancer component ready.')
        },
        data: () => ({
            state: {
                loading: false,
            },
            user: window.Laravel.user,
        }),
        computed: {

        },
        methods: {
            confirmBecomeFreelancer() {
                this.$refs['become-freelancer'].open();
            },
            becomeFreelancer(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                this.$http.post('/apiv1/profile/update/' + this.user.id + '/role', {
                    role : 'client_freelancer'
                }).then((response) => {

                    this.user = response.body.user;
                    this.$root.user = response.body.user;
                    this.$root.showNotification(response.body.message);

                    /* Redirect to edit profile after successful role conversion */
                     var vm = this;
                    setTimeout(function() {
                        window.location = vm.user.profile_edit_link;
                    },3000);



                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log(response);
                });

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