<!--

Usage: <sage-share-job></sage-share-job>

-->

<template>
    <span>
        <md-button class="md-raised md-primary btn-block no-margin-left no-margin-right" @click="state.open = !state.open">
            <md-icon class="font-size-14">share</md-icon> Share This Job
        </md-button>
        <div class="text-center" :class="{hidden : state.open}">
            <h5>Job URL: {{ shareUrl }}</h5>
            <div class="addthis_inline_share_toolbox"
                 :data-url="shareUrl"
                 :data-title="shareTitle"
                 :data-description="shareTitle"
            ></div>
            <div class="clearfix"></div>
        </div>
    </span>
</template>

<script>
    export default {
        mounted() {
            console.log('Job share component ready.')

            var vm = this;
            appBus.$on('shareClickBack', function (event) {
                setTimeout(function() {
                    vm.recordClick(event);
                },1000);
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
            shareUrl: function() {
                return window.location.protocol + "//" + window.location.hostname + "/job/" + this.shared.job.id;
            },
            shareTitle: function() {
                return "View " + this.shared.job.title + " on SageGroupy";
            }
        },
        methods : {
            recordShare(event) {
                var newEvent = {
                    type: 'job',
                    view_type: 'share',
                    job_id: this.shared.job.id,
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
                    type: 'job',
                    view_type: 'share_click',
                    job_id: this.shared.job.id,
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
