<template>
    <div>
        <div  v-if="type === 'button' && user.is_freelancer">
            <md-button class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="confirmJoinAgency">Join This Agency</md-button>
        </div>
        <md-menu-item v-if="type === 'menu-item' && user.is_freelancer" @click="confirmJoinAgency">
            <md-icon clas="md-primary">event_available</md-icon>
            <span>Join Agency</span>
        </md-menu-item>

        <md-dialog-confirm
                md-title="Join An Agency"
                md-content-html="Are you sure you would like to apply to join this agency? <br/> Your approval will be determined by the agency leader."
                md-ok-text="Confirm"
                md-cancel-text="Cancel"
                @close="joinAgency"
                ref="join-agency">
        </md-dialog-confirm>
    </div>

</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log(' Join Agency component ready.')
        },
        props: ['agencyId','type'],
        data: () => ({
            state: {
                loading: false,
            },
            user: window.Laravel.user,
            shared: window.sageSource
        }),
        computed: {},
        methods: {
            confirmJoinAgency() {
                this.$refs['join-agency'].open();
            },
            joinAgency(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                this.$http.post('/apiv1/agencies/join/' + this.agencyId, {
                    'user_id': this.user.id
                }).then((response) => {
                    this.$root.showNotification(response.body.message);
                }, (response) => {
                    this.$root.showNotification(response.body.message);
                    console.log(response);
                });

            }

        }
    }
</script>