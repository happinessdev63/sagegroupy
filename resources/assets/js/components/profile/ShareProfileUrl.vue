<!--

Usage: <sage-share-profile-url> </sage-share-profile-url>

-->

<template>
    <span>
        <md-button v-if="shared.profile.is_freelancer" class="md-primary md-raised btn-block no-margin-left no-margin-right" @click="state.open = !state.open">
            <md-icon>share</md-icon> Share Profile
        </md-button>
        <div :class="{hidden : state.open}">
            <h5>Profile URL: {{ shared.profile.public_profile_url }}</h5>
            <div class="addthis_inline_share_toolbox"
                 :data-url="shared.profile.public_profile_url"
                 :data-title="shareTitle"
                 :data-description="shareTitle"
            ></div>
            <div class="clearfix"></div>
        </div>
    </span>
</template>


<script type="text/babel">
    export default {
        mounted() {
            console.log(' Profile share component ready.')
            //this.state.open = false;
            var vm = this;
            appBus.$on('shareClickBack', function (event) {
                setTimeout(function () {
                    vm.recordClick(event);
                }, 1000);
            });
            appBus.$on('sharedProfile', function (event) {
                setTimeout(function () {
                    vm.recordShare(event);
                }, 1000);
            });

        },
        data() {
            return {
                state: {
                    open: true,
                },
                shared: window.sageSource,
            }
        },
        computed: {
            shareTitle: function() {
                return "View " + this.shared.profile.name + "s' Freelancer Profile on SageGroupy"
            }
        },
        methods : {
            recordShare(event) {
                var newEvent = {
                    type: 'user',
                    view_type: 'share',
                    user_id: this.shared.profile.id,
                    source: event.data.service
                }
                this.$http.post('/apiv1/events/view', newEvent).then((response) => {
                    console.log('share event recorded');
                }, (response) => {
                    console.log('share event NOT recorded');
                });
            },
            recordClick(event) {
                var newEvent = {
                    type: 'user',
                    view_type: 'share_click',
                    user_id: this.shared.profile.id,
                    source: event.data.service
                }
                this.$http.post('/apiv1/events/view', newEvent).then((response) => {
                    console.log('click event recorded');
                }, (response) => {
                    console.log('click event NOT recorded');
                });
            },
            openDialog(ref) {
                this.$refs[ref].open();
            }
        }
    }
</script>
