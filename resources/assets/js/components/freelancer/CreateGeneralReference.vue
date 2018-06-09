<template>
    <div>
        <div class="col-lg-12">
            <div class="col-sm-12 col-md-12 margin-top-15 no-padding">
                <p class="font-size-12">General references allow you to collect references from people who will vouch for you. This could include previous employers, friends, past colleagues or other people you have intereacted with. Enter the details of your general reference and enter the email of the person you are requesting a reference from. The reference provider will be prompted to create an account on SageGroupy and provide a reference for your profile. </p>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="col-lg-12">
                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.title  }">
                    <label>Reference Title</label>
                    <md-input required type="text" v-model="referenceData.title"></md-input>
                    <span v-if="errors.title" class="md-error">{{ errors.title[0] }}</span>
                </md-input-container>

                <label class="text-muted font-size-16 font-weight-500 margin-top-30"
                       v-bind:class="{ 'text-invalid' : errors.description  }">Please enter your reference description / details.</label>
                <span v-if="errors.description" class="text-invalid"> <br/>{{ errors.description[0] }}</span>

                <vue-editor
                        id="refDescriptionText"
                        :editorToolbar="$root.editorToolbar"
                        v-model="referenceData.description">
                </vue-editor>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.client_email  }">
                    <label>Email of User For Reference</label>
                    <md-input required type="text" v-model="referenceData.client_email"></md-input>
                    <span v-if="errors.client_email" class="md-error">{{ errors.client_email[0] }}</span>
                </md-input-container>

                <div class="margin-top-40">
                    <label class="text-muted font-size-16 font-weight-500"
                           v-bind:class="{ 'text-invalid' : errors.client_message  }">Please enter a meaningful message to the person you are requesting a reference from.</label>
                    <span v-if="errors.client_message" class="text-invalid"> <br/>{{ errors.client_message[0] }}</span>
                    <vue-editor
                            id="clientMessageText"
                            :editorToolbar="$root.editorToolbar"
                            v-model="referenceData.client_message"
                    >
                    </vue-editor>
                </div>

                <div class="md-input-container md-theme-default no-margin-bottom">
                    <label>Relevant Files (Screenshots etc.)</label>
                </div>
                <md-list v-if="referenceData.files.length > 0" class="custom-list md-double-line">
                    <md-list-item v-for="(file, fileIndex) in referenceData.files" :key="fileIndex">

                        <div v-if="file.is_image" class="margin-right-20">
                            <a class="sageImage" :href="file.url" rel="image-files" target="_blank">
                                <img :src="file.url" class="img-thumbnail" width="80" height="80" :alt="file.name">
                            </a>
                        </div>
                        <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                            <a :href="file.url" target="_blank">
                                <md-icon class="font-size-40">
                                    assignment
                                </md-icon>
                            </a>
                        </div>
                        <div class="md-list-text-container" @click="editFile(file)">
                            <span>{{ file.name }}</span>
                            <p>{{ file.description }}</p>
                        </div>

                        <md-button class="md-icon-button md-list-action" @click="confirmDeleteFile(file)">
                            <md-icon class="md-primary">delete</md-icon>
                        </md-button>
                        <md-button class="md-icon-button md-list-action" @click="editFile(file)">
                            <md-icon class="md-primary">edit</md-icon>
                        </md-button>

                        <md-divider class="md-inset"></md-divider>
                    </md-list-item>
                </md-list>

                <div class="clearfix"></div>

                <sage-dropzone
                        ref="referenceFiles"
                        id="referenceFiles"
                        v-bind:thumbnailHeight="120"
                        v-bind:thumbnailWidth="120"
                        v-bind:showRemoveLink="true"
                        :maxFileSizeInMB="20"
                        url="/apiv1/files/create"
                        acceptedFileTypes="image/*,application/pdf,.psd,.doc*,.odf"
                        v-on:vdropzone-success="fileSuccess"
                ></sage-dropzone>

                <div class="clearfix"></div>
                <md-button class="md-primary pull-right" @click="saveReference()">
                    <md-icon>assignment</md-icon>
                    Save Reference 
                </md-button>
            </div>

        </div>
        <div class="clearfix"></div>

        <!-- Media Editing Modal -->
        <ui-modal
                ref="fileModal"
                transition="ui-modal-fade"
        >
            <div slot="header">
                Edit {{ selectedFile.name }}
            </div>

            <md-input-container>
                <label>File Name</label>
                <md-input type="text" v-model="selectedFile.name" :maxlength="60" @focus.native="$event.target.select(); $event.target.value='';" ></md-input>
            </md-input-container>

            <md-input-container>
                <label>File Description</label>
                <md-input type="text" v-model="selectedFile.description" :maxlength="60" @focus.native="$event.target.select(); $event.target.value='';"></md-input>
            </md-input-container>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('fileModal')">Close</md-button>
                <md-button class="md-primary" @click="saveEditedFile">Save</md-button>
            </div>
        </ui-modal>
        <md-dialog-confirm
                md-title="Delete this file"
                md-content-html="Are you sure you want to remove this file?"
                md-ok-text="Delete File"
                md-cancel-text="Cancel"
                @close="deleteFile"
                ref="delete-file">
        </md-dialog-confirm>

    </div>
</template>
<script type="text/babel">
    import {VueEditor} from 'vue2-editor';
    export default {
        mounted() {
            console.log(' Create general reference component ready.')

			this.referenceData = this.defaultReferenceData;

        },
        props: ['reference'],
		computed: {

			clientInviteMessage: function () {

                /* Message for freelancers */
				if (this.user && this.user.id) {
					return `Hi,\n\nMy name is ${this.user.name}, I would really appreciate it if you could provide a quick reference for my portfolio on SageGroupy. It will only take a minute of your time.\n\nThank You,\n${this.user.name}`;
				}

			}
		},
        components: {
            VueEditor
        },
        data() {
            return {
                user: window.Laravel.profile,
                errors: {},
                state: {
                    saving: false
                },
                description: '',
                referenceData: {
                    title: '',
                    type: 'reference',
                    description: '',
                    files: [],
                    client_email : '',
                    client_message : ''
                },
				defaultReferenceData: {
					title: '',
					type: 'reference',
					description: '',
					files: [],
					client_email: '',
					client_message: ''
				},
                selectedFile: {},
                notifyPosition: 'right',
                allowHtmlNotifications: true,
                queueNotifications: true,
            }
        },
        methods: {
            updateDescription(html) {
                this.referenceData.description = html;
            },
            syncReference: function(id) {
                this.$http.get('/apiv1/references/' + this.reference, this.reference).then((response) => {
                    this.state.saving = false;
                    this.referenceData = response.body.results;
                    this.description = this.referenceData.description;
                    this.$root.showNotification(response.body.message);
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }
                });
            },
            fileSuccess: function (file) {
                console.log(file);
                var response = JSON.parse(file.xhr.response);

                /* Check if the file is an image */
                var isImage = false;
                if (file.type.indexOf('image') !== -1) {
                    var isImage = true;
                }

                this.referenceData.files.push({
                    name: file.name,
                    size: file.size,
                    is_image: isImage,
                    id: response.id,
                    path: response.path,
                    url: response.url,
                    'user_path': response['user_path'],
                    description: 'No description set.'
                })
            },
            confirmDeleteFile(file) {
                this.selectedFile = file;
                this.$refs['delete-file'].open();
            },
            deleteFile(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                var index = this.referenceData.files.indexOf(this.selectedFile);
                this.referenceData.files.splice(index, 1);
                this.$root.showNotification('File ' + this.selectedFile.name + ' removed successfully.');
                this.selectedFile = {};

            },
            editFile(file) {
                this.selectedFile = file;
                this.openModal('fileModal');
            },
            saveEditedFile() {
                this.closeModal('fileModal');
            },
            openModal(ref) {
                this.$refs[ref].open();
            },
            closeModal(ref) {
                this.$refs[ref].close();
            },
            setReference(ref) {
            	this.referenceData = ref;
            },
            saveReference() {

                this.errors = {}
                this.state.saving = true;

                $('html, body').animate({scrollTop: 0}, 'slow');

				if (this.agency && this.agency.id) {
					this.referenceData.agency_id = this.agency.id;
				}

                if (!this.referenceData.id) {
                    var apiUrl = '/apiv1/references/create';
                } else {
                    var apiUrl = '/apiv1/references/update/' + this.referenceData.id;
                }

                this.$http.post(apiUrl, this.referenceData).then((response) => {
                    this.state.saving = false;
                    this.referenceData.id = response.body.results.id;
                    this.$root.showNotification(response.body.message);
                    appBus.$emit('generalReference:saved');
                    appBus.$emit('closeFullModal','createGeneralReferenceModal');
                    this.referenceData = this.defaultReferenceData;
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);

                    } else {
                        this.$root.showNotification('Please enter all the required fields and try again.');
                        this.errors = response.body;
                    }
                });


            }
        }
    }
</script>
<style type="text/css">
    .ql-editor {
        min-height: 200px !important;
    }
</style>