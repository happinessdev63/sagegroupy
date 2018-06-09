<!--

Usage: <sage-create-reference-job> </sage-create-reference-job>

-->

<template>
    <div>
        <div class="col-lg-12">
            <div class="col-sm-12 col-md-6 margin-top-15 no-padding">
                <h4>Reference Job Details</h4>
                <p class="font-size-12">Reference jobs allow you to showcase past projects on your profile to attract potential clients.</p>
            </div>
            <div class="col-sm-12 col-md-6 margin-top-15 no-padding">
                <!--
                <div class="pull-right">
                    <md-button href="#" class="md-primary">Import Jobs</md-button>
                    <ui-snackbar-container
                            ref="notifyContainer"
                            :position="notifyPosition"
                            :queue-snackbars="queueNotifications"
                            :allowHtml="allowHtmlNotifications"
                    ></ui-snackbar-container>
                </div>
                -->
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="col-lg-12">
                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.title  }">
                    <label>Job Title</label>
                    <md-input required type="text" v-model="jobData.title"></md-input>
                    <span v-if="errors.title" class="md-error">{{ errors.title[0] }}</span>
                </md-input-container>

                <label class="text-muted font-size-16 font-weight-500 margin-top-30"
                       v-bind:class="{ 'text-invalid' : errors.description  }">Please enter a job description / summary.</label>
                <span v-if="errors.description" class="text-invalid"> <br/>{{ errors.description[0] }}</span>

                <vue-editor
                        id="descriptionText"
                        :editorToolbar="$root.editorToolbar"
                        v-model="jobData.description">
                </vue-editor>

                <div class="form-group">
                    <div class="md-input-container md-theme-default no-margin-bottom">
                        <label>Options</label>
                    </div>
                    <md-switch v-model="jobData.public" class="md-primary"> Show job on your public profile.</md-switch> <br/>
                    <md-switch v-model="jobData.public_files" class="md-primary"> Show job files on your public profile.</md-switch> <br/>
                    <md-switch v-model="jobData.invite_client" class="md-primary"> Invite client to provide reference.</md-switch>

                    <div v-if="jobData.invite_client">
                        <md-input-container v-bind:class="{ 'md-input-invalid' : errors.client_email  }">
                            <label>Client Email</label>
                            <md-input required type="text" v-model="jobData.client_email"></md-input>
                            <span v-if="errors.client_email" class="md-error">{{ errors.client_email[0] }}</span>
                        </md-input-container>

                        <div class="margin-top-40">
                        <label class="text-muted font-size-16 font-weight-500"
                               v-bind:class="{ 'text-invalid' : errors.client_message  }">Please enter a meaningful message to your client.</label>
                        <span v-if="errors.client_message" class="text-invalid"> <br/>{{ errors.client_message[0] }}</span>
                        <vue-editor
                                id="clientMessageText"
                                :editorToolbar="$root.editorToolbar"
                                v-model="jobData.client_message"
                                >
                        </vue-editor>
                        </div>
                    </div>
                </div>

                <div class="md-input-container md-theme-default no-margin-bottom">
                    <label>Relevant Files (Specifications / Examples / Contracts etc.)</label>
                </div>
                <md-list v-if="jobData.files.length > 0" class="custom-list md-double-line">
                    <md-list-item v-for="(file, fileIndex) in jobData.files" :key="fileIndex">

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
                        ref="jobFiles"
                        id="jobFiles"
                        v-bind:thumbnailHeight="120"
                        v-bind:thumbnailWidth="120"
                        v-bind:showRemoveLink="true"
                        :maxFileSizeInMB="20"
                        url="/apiv1/files/create"
                        acceptedFileTypes="image/*,application/pdf,.psd,.doc*,.odf"
                        v-on:vdropzone-success="fileSuccess"
                ></sage-dropzone>

                <div class="clearfix"></div>
                <md-button class="md-primary pull-right" @click="saveJob()">
                    <md-icon>assignment</md-icon>
                    Save Reference Job
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
            console.log(' Create reference job component ready.')

			if (this.agency && this.agency.id) {
				this.jobData.agency_id = this.agency.id;
			}

            if (this.job) {
                this.syncJob(this.job);
            }

            this.jobData.client_message = this.clientInviteMessage;

        },
        props: ['job', 'agency'],
        computed : {

        	clientInviteMessage: function() {

        		/* Return the associated client message if one has already been set */
        		if (this.jobData.client_message && this.jobData.client_message.length > 1) {
        			return this.jobData.client_message;
                }

                /* Message for agencies */
				if (this.agency && this.agency.id) {
					return `Hi,\n\nMy name is ${this.agency.owner.name} and I represent an agency on SageGroupy, ${this.agency.name}. We completed a job for you in the past and would really appreciate it if you could provide a quick reference for our portfolio on SageGroupy. It will only take a minute of your time.\n\nThank You,\n${this.agency.owner.name}`;
				}

				/* Message for freelancers */
				if (this.user && this.user.id) {
					return `Hi,\n\nMy name is ${this.user.name}, I completed a job for you in the past and would really appreciate it if you could provide a quick reference for my portfolio on SageGroupy. It will only take a minute of your time.\n\nThank You,\n${this.user.name}`;
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
                clientMessage: '',
                jobData: {
                    title: '',
                    type: 'reference',
                    description: '',
                    payment_terms: '',
                    files: [],
                    public: true,
                    public_files: true,
                    invite_client : false,
                    client_email : '',
                    client_message : ''
                },
				defaultJobData: {
					title: '',
					type: 'reference',
					description: '',
					payment_terms: '',
					files: [],
					public: true,
					public_files: true,
					invite_client: false,
					client_email: '',
					client_message: ''
				},
                selectedFile: {},
                notifyPosition: 'right',
                allowHtmlNotifications: true,
                queueNotifications: true,
                cropper: {
                    show: false,
                    params: {
                        token: '123456798',
                        name: 'avatar'
                    },
                    imgDataUrl: ''
                },
            }
        },
        methods: {
            updateDescription(html) {
                this.jobData.description = html;
            },
            updateClientMessage(html) {
                this.jobData.client_message = html;
            },
            syncJob: function(id) {
                this.$http.get('/apiv1/jobs/' + this.job, this.job).then((response) => {
                    this.state.saving = false;
                    this.jobData = response.body.results;
					this.jobData.client_message = this.clientInviteMessage;
                    this.description = this.jobData.description;
                    this.clientMessage = this.jobData.client_message;
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

                this.jobData.files.push({
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

                var index = this.jobData.files.indexOf(this.selectedFile);
                this.jobData.files.splice(index, 1);
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
            saveJob() {

                this.errors = {}
                this.state.saving = true;

                $('html, body').animate({scrollTop: 0}, 'slow');

				if (this.agency && this.agency.id) {
					this.jobData.agency_id = this.agency.id;
				}

                if (!this.jobData.id) {
                    var apiUrl = '/apiv1/jobs/createReference/';
                } else {
                    var apiUrl = '/apiv1/jobs/update/' + this.jobData.id;
                }

                this.$http.post(apiUrl, this.jobData).then((response) => {
                    this.state.saving = false;
                    this.jobData.id = response.body.results.id;
                    this.$root.showNotification(response.body.message);
                    appBus.$emit('referenceJob:saved');
                    appBus.$emit('closeFullModal','createReferenceModal');
                    this.jobData = this.defaultJobData;
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