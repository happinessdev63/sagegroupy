<template>
    <div>
        <div v-if="shared.notification" class="modal fade" id='bulkNotificationModal' tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" @click.native="close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Send Mass Notification</h4>
                    </div>
                    <div id="messageHolder" class="modal-body height-400 max-height-400">

                        <div>

                            <div class="form-group">
                                <label>Select User Segment</label>
                                <select class="form-control" required name="segment" id="segment" v-model="state.message.segment">
                                    <option value="all">All Users</option>
                                    <option value="active">Active Users</option>
                                    <option value="freelancers">Freelancers</option>
                                    <option value="clients">Clients</option>
                                    <option value="agency_owners">Agency Owners</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Message Title</label>
                                <input required type="text" name="title" v-model="state.message.title" :disabled="state.sending" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea required name="message" v-model="state.message.message" :disabled="state.sending" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <md-button class="md-primary" @click="send">Send Notifications</md-button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log('Bulk notification component ready.')


            var vm = this;
            vm.state.message.from_user_id = vm.owner;
            vm.state.message.owner_id = vm.owner;

            appBus.$on('openBulkNotifications', function () {
                vm.open();
            });

        },
        created() {

        },
        props: ['owner'],
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                errors: {},
                state: {
                    message: {
                        from_user_id : null,
                        to_user_id: null,
                        owner_id: null,
                        title: '',
                        message: '',
                        type: 'user-message',
                        segment: null
                    },
                    loading: true,
                    sending: false
                }
            }
        },
        computed: {},
        filters: {
        },
        methods: {
            open(notification) {
                $('#bulkNotificationModal').modal();
            },
            close() {

            },
            send() {

                if (!this.state.message.message || this.state.sending) {
                    this.$root.showNotification("Please enter a message to send first.");
                    return;
                }

                if (!this.state.message.segment) {
                    this.$root.showNotification("Please select a user segment first.");
                    return;
                }

                if (!this.state.message.title) {
                    this.$root.showNotification("Please enter a message title first.");
                    return;
                }


                this.state.sending = true;
                this.shared.chatMessages.push(_.clone(this.state.message));

                this.$http.post('/apiv1/notifications/sendBulkMessages', this.state.message).then((response) => {
                    this.state.message.message = null;
                    this.state.message.title = null;
                    this.state.sending = false;
                    this.$root.showNotification(response.body.message);

                }, (response) => {
                    this.state.sending = false;
                    this.$root.showNotification(response.body.message);
                    console.log("Error sending message.");
                    console.log(response);
                });
            }
        }
    }
</script>