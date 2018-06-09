<template>
    <div v-if="user.is_freelancer && !user.is_client">
        <md-button class="md-primary md-raised btn-block no-margin-right no-margin-left" @click="confirmBecomeClient">Hire a Freelancer</md-button>
        <md-dialog-confirm
                md-title="Hire a Freelancer"
                md-content-html="After confirming you will be able to create jobs and request other Freelancers to bid on your jobs.
                <br/>
                You will still have all the Freelancer features available as well. Are you sure you would like to continue?"
                md-ok-text="Confirm"
                md-cancel-text="Cancel"
                @close="becomeClient"
                ref="become-client">
        </md-dialog-confirm>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log(' Become client component ready.')
        },
        data: () => ({
            state: {
                loading: false,
            },
            user: window.Laravel.user,
        }),
        computed: {},
        methods: {
            confirmBecomeClient() {
                this.$refs['become-client'].open();
            },
            becomeClient(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                this.$http.post('/apiv1/profile/update/' + this.user.id + '/role', {
                    role: 'client_freelancer'
                }).then((response) => {

                    this.user = response.body.user;
                    this.$root.user = response.body.user;
                    this.$root.showNotification(response.body.message);


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