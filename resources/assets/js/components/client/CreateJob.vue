<!--

Usage: <sage-create-job> </sage-create-job>

-->

<template>
    <div>
        <div class="col-lg-12">
            <div class="col-sm-12 col-md-6 margin-top-15 no-padding">
                <h4>Job Details</h4>
                <p class="font-size-12">After creating your job you will have a chance to search for freelancers and invite them to bid on your job.</p>
            </div>
            <div class="col-sm-12 col-md-6 margin-top-15 no-padding">
                <div class="pull-right">
                    <!--
                    <md-button href="#" class="md-primary">Utilize Pro Service</md-button>
                    -->
                    <ui-snackbar-container
                            ref="notifyContainer"
                            :position="notifyPosition"
                            :queue-snackbars="queueNotifications"
                            :allowHtml="allowHtmlNotifications"
                    ></ui-snackbar-container>
                    <md-button v-if="this.jobData.public" class="md-primary" @click="updatePublic()">
                        <md-icon>visibility</md-icon>
                        public
                    </md-button>

                    <md-button v-if="!this.jobData.public" class="md-primary" @click="updatePublic()">
                        <md-icon>visibility_off</md-icon>
                        private
                    </md-button>
                </div>
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
                       v-bind:class="{ 'text-invalid' : errors.description  }">Job Details / Summary *</label>
                <span v-if="errors.description" class="text-invalid"> <br/>{{ errors.description[0] }}</span>
                <vue-editor
                        id="jobDescriptionText"
                        :editorToolbar="$root.editorToolbar"
                        v-model="jobData.description">
                </vue-editor>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.payment_terms }">
                    <label>Payment Terms</label>
                    <md-select required name="payment_terms" id="payment_terms" v-model="jobData.payment_terms">
                        <md-option value="after_completion">Payment After Job Completion</md-option>
                        <md-option value="milestone">Milestone Based Payments</md-option>
                        <md-option value="hourly_weekly">Hourly Rate, Paid Weekly</md-option>
                        <md-option value="hourly_biweekly">Hourly Rate, Paid Bi-Weekly</md-option>
                        <md-option value="salary">Salary</md-option>
                        <md-option value="tbd">To Be Determined</md-option>
                    </md-select>
                    <span v-if="errors.payment_terms" class="md-error">{{ errors.payment_terms[0] }}</span>
                </md-input-container>

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

                        <md-divider class="md-inset-110"></md-divider>
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
                    Save Job
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
                <md-input type="text" v-model="selectedFile.name" :maxlength="60" @focus.native="$event.target.select(); $event.target.value='';"></md-input>
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
            console.log(' Create job component ready.')
            if (this.job) {
                this.syncJob(this.job);
            }
        },
        props: ['job'],
        computed : {
            isDescriptionValid : function() {
                if (!this.errors.description) {
                    return false;
                }
                if (this.errors.description.length > 0) {
                    return true;
                }
                return false;
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
                jobData: {
                    title: '',
                    description: '',
                    'payment_terms': '',
                    files: [],
                    public: false,
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
            updatePublic() {
              this.jobData.public = !this.jobData.public;
            },
            updateDescription(html) {
                this.jobData.description = html;
            },
            syncJob: function(id) {
                this.$http.get('/apiv1/jobs/' + this.job, this.job).then((response) => {
                    this.state.saving = false;
                    this.jobData = response.body.results;
                    this.description = this.jobData.description;
                    this.showJob = this.jobData.public;
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
                alert();
                if (!this.jobData.id) {
                    var apiUrl = '/apiv1/jobs/create/';
                } else {
                    var apiUrl = '/apiv1/jobs/update/' + this.jobData.id;
                }

                this.$http.post(apiUrl, this.jobData).then((response) => {
                    this.state.saving = false;

                    this.$root.showNotification(response.body.message);

                    /* Redirect to freelancer search after job is saved if this the first time,
                       otherwise redirect to job listings.
                     */
					if (!this.jobData.id) {
                        /* Set job id */
						this.jobData.id = response.body.results.id;
						setTimeout(() => {
							window.location = '/search?intent=newJob&jobId=' + this.jobData.id;
                        },4000);
					} else {
						setTimeout(() => {
							window.location = '/userJobs';
						}, 4000);
					}




                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }
                });


            },
            showNotification(message) {
                this.$refs.notifyContainer.createSnackbar({
                    message: message,
                    action: '',
                    actionColor: 'accent',
                    duration: 5000
                });
            },
        }
    }
</script>
