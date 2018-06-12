<template>
    <div>
        <md-button class="md-primary pull-right" @click.native="openParentModal('saveLinkModal');">
            <md-icon>add</md-icon>
            Add New Link
        </md-button>
        <div class="clearfix"></div>

        <!-- Links and References From Other Sites -->
        <md-list class="custom-list md-dense">
            <md-list-item v-for="(link,index) in links" :key="index">

                <md-icon  class="font-size-40">
                    bookmark
                </md-icon>

                <div class="md-list-text-container margin-top-20 padding-bottom-10">
                    <div class="margin-bottom-10">
                        <span> <a :href="link.url" rel="nofollow" target="_blank" class="link-primary">{{ link.title }}</a></span>
                    </div>
                    <p>{{ link.short_description }}</p>

                    <div v-if="link.files && link.files.length > 0" class="margin-top-10 pull-right">
                        <a v-for="(file,index) in link.files" v-if='file.is_image' class="sageImage" :href="file.url" rel="image-files">
                            <img :src="file.url" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                        </a>
                    </div>

                </div>

                <div>
                    <md-button class="md-icon-button md-list-action" @click="confirmDeleteLink(link)">
                        <md-icon class="md-primary">delete</md-icon>
                    </md-button>
                    <md-button class="md-icon-button md-list-action" @click="editLink(link)">
                        <md-icon class="md-primary">edit</md-icon>
                    </md-button>
                </div>

                <md-divider class="md-inset"></md-divider>
            </md-list-item>
        </md-list>

        <!-- No data -->
        <div v-if="links && links.length == 0">
            <md-list class="custom-list md-dense">
                <md-list-item>
                    <md-icon class="font-size-30">
                        announcement
                    </md-icon>
                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <p>You have not added any links yet.</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <div class="col-lg-12 margin-top-10">
            <md-icon class="pull-left text-muted margin-bottom-40">info</md-icon>
            <div class="margin-top-5 margin-left-20">
                Add links to your other freelancing profiles, social media accounts, your 3rd party portfolios or any other links that may be of interest to potential clients.
            </div>
        </div>

        <md-dialog-confirm
                md-title="Delete this link?"
                md-content-html="Are you sure you want to remove this link from your profile?"
                md-ok-text="Delete Link"
                md-cancel-text="Cancel"
                @close="deleteLink"
                ref="deleteLink">
        </md-dialog-confirm>

    </div>
</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log('Profile edit links component ready.')

            var vm = this;
            appBus.$off('savedLink');
            appBus.$on('savedLink', function (event) {
               vm.saveLink();
            });

        },
        props: {
            agency: {
                type: Number,
                default: null
            }
        },
        data() {
            return {
                shared: window.sageSource,
                errors: {},
                state: {
                    saving: false,
                },
            }
        },
        computed: {
            links: function() {
                if (this.agency) {
                    return this.shared.agency.links;
                }
                if (this.shared.profile) {
                	return this.shared.profile.links;
                }

                return [];
            }
        },
        methods: {
            saveLink() {
                var url = '/apiv1/links/create';
                if (this.shared.link.id) {
                    url = '/apiv1/links/update/' + this.shared.link.id;
                }

                if (this.agency) {
                    this.shared.link.agency_id = this.agency;
                }

                this.$http.post( url, this.shared.link).then((response) => {
                    this.$root.showNotification(response.body.message);
                    this.closeParentModal('saveLinkModal');

                    console.log('set default link');
                    if (this.agency) {
                        appBus.$emit('refreshAgency');
                    } else {
                        appBus.$emit('refreshProfile');
                    }

                }, (response) => {
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.$root.showNotification('Please enter all the required fields and try again.');
                        this.shared.errors = response.body;
                    }

                });
            },
            editLink(link) {
                this.shared.link = link;
                this.openParentModal('saveLinkModal');
            },
            confirmDeleteLink(link) {
                this.shared.link = link;
                this.$refs['deleteLink'].open();
            },
            deleteLink(confirmation) {

                if (confirmation !== 'ok') {
                    return;
                }

                var apiUrl = '/apiv1/links/delete/' + this.shared.link.id;
                this.$http.post(apiUrl).then((response) => {
                    this.$root.showNotification(response.body.message);
                    if (this.agency) {
                        appBus.$emit('refreshAgency');
                    } else {
                        appBus.$emit('refreshProfile');
                    }
                }, (response) => {
                    this.$root.showNotification(response.body.message);
                });

            },
            openParentModal(ref) {
                appBus.$emit('openParentModal', ref);
            },
            closeParentModal(ref) {
                appBus.$emit('closeParentModal', ref);
            },
        }
    }
</script>
