<template>
    <div class="bg-white" :class="getChatClass" id="chat">
        <div v-if="shared.notification && shared.notification.sender" id='chatModal' tabindex="-1" role="dialog">
            <div>
                <div >
                    <div class="bg-dark padding-10">
                        <md-button class="pull-right m-t-n-5" @click="closeChat()"><md-icon>close</md-icon></md-button>
                        <h4 class="modal-title"><md-icon>chat</md-icon> Chat With {{ shared.notification.sender.name }}</h4>
                    </div>
                    <div id="messageHolder" class="min-height-500 padding-10 chat-messages bg-white">

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

                        <div v-else>
                            <div v-for="(message, rowIndex) in shared.chatMessages">
                                <div class="border-radius-5 bg-grey-100 padding-10 margin-bottom-15" v-if="owner == message.to_user_id">
                                    <img v-if="message.sender" class="img-thumbnail img-responsive pull-left" :src="message.sender.avatar" style="width: 45px; height: 45px;"/>
                                    <div class="pull-left">
                                        <p class="margin-left-20 font-size-15">
                                            {{ message.message }}
                                        </p>
                                        <span class="text-muted font-size-12 margin-left-20">{{ message.nice_date }}</span></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div v-else class="border-radius-5 border-2 border-grey-100 text-right padding-10 margin-bottom-15">
                                    <p class="margin-left-20 font-size-15">
                                        {{ message.message }}
                                    </p>
                                    <span class="text-muted font-size-12">{{ message.nice_date }}</span>
                                </div>
                            </div>

                            <div v-if="shared.chatMessages.length == 0">
                                <div class="border-radius-5 bg-grey-100 padding-10 margin-bottom-15">
                                    <div class="pull-left">
                                        <p class="margin-left-20 font-size-15">
                                             No messages yet, send a message to get started.
                                        </p>
                                        <span class="text-muted font-size-12 margin-left-20">just now</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="padding-10 text-center">
                        <textarea name="message" v-model="state.message.message" :disabled="state.sending" class="form-control" @keydown="checkForSending"></textarea>
                        <md-button class="md-primary md-raised btn-block no-margin-right no-margin-left" @click="sendMessage">Send Message</md-button>
                        <span class="text-muted">Hold Shift + Enter for new lines.</span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            console.log('Chat component ready.')


            var vm = this;
            vm.options.owner = vm.owner;
            vm.state.message.from_user_id = vm.owner;
            vm.state.message.owner_id = vm.owner;

            appBus.$on('openChat', function (notification) {
                vm.options.sender = notification.from_user_id;
                vm.options.receiver = notification.to_user_id;
                vm.state.message.to_user_id = notification.from_user_id;
                vm.options.status = '';
                vm.options.type = 'user-message'
                vm.state.message.type = notification.type;
                vm.openChat(notification);
                vm.refreshChat();
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
                recurringRefresh: null,
                options: {
                    owner: null,
                    sender: null,
                    receiver: null,
                    type: null,
                    pager: {
                        page: 1,
                        size: 25
                    },
                    sort: {
                        name: 'created_at',
                        type: 'asc'
                    },
                    paginate: false,
                    chats: true,
                    status: 'unread',
                    search: '',
                    filter: null
                },
                state: {
                    message: {
                        from_user_id : null,
                        to_user_id: null,
                        owner_id: null,
                        title: '',
                        message: '',
                        nice_date: 'just now',
                        type: null,
                        sender: window.sageSource.user
                    },
                    loading: true,
                    sending: false,
                    open: false
                }
            }
        },
        computed: {
        	getChatClass : function() {
        		if (this.state.open) {
        			return 'open';
                } else {
        			return 'closed';
                }
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
            openChat(notification) {
                this.shared.notification = notification;
                this.state.open = true;

                var vm = this;
                this.recurringRefresh = setInterval(function() {
                   vm.refreshChat(true);
                },3000);
            },
            closeChat() {
              if (this.recurringRefresh) {
                  console.log('clearing chat refresh');
                  clearInterval(this.recurringRefresh);
                  this.recurringRefresh = null;
              }

              this.state.open = false;

            },
            checkForSending(e) {
				if (e.keyCode == 13) {
					if (e.shiftKey == true) {
						return true;
					}
					else {
						this.sendMessage();
						return false;
					}
					return false;
				}
            },
            sendMessage() {

                if (!this.state.message.message || this.state.sending) {
                    this.$root.showNotification("Please enter a message to send first.");
                    return;
                }


                this.state.sending = true;
                this.shared.chatMessages.push(_.clone(this.state.message));

                this.$http.post('/apiv1/notifications/sendMessage', this.state.message).then((response) => {
                    this.state.message.message = null;
                    this.state.sending = false;
                    $('#messageHolder').scrollTop($('#messageHolder')[0].scrollHeight);

                }, (response) => {
                    this.state.sending = false;
                    this.$root.showNotification(response.body.message);
                    console.log("Error sending message.");
                    console.log(response);
                });
            },
            refreshChat(noLoader)
            {
                if (!noLoader) {
                    this.state.loading = true;
                }

                var numMessages = this.shared.chatMessages.length;
                // this.$http.get('/apiv1/notifications', {params: this.options}).then((response) => {
                //     if (this.options.paginate) {
                //         this.shared.chatMessages = response.body.data;
                //     } else {
                //         this.shared.chatMessages = response.body;
                //     }
                //
                //     this.state.loading = false;
                //
                //     /* Scroll to bottom if there is a new message and on first load */
                //     if (this.shared.chatMessages.length > numMessages || numMessages == 0) {
                //         setTimeout(function () {
                //             $('#messageHolder').scrollTop($('#messageHolder')[0].scrollHeight);
                //         }, 800)
                //     }
                //
                // }, (response) => {
                //     this.$root.showNotification(response.body.message);
                //     console.log("Error loading notifications");
                //     console.log(response);
                // });
            }
        }
    }
</script>
