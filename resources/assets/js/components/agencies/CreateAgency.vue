<!--

Usage: <sage-create-job> </sage-create-job>

-->

<template>
    <div>
        <div class="col-lg-12">
            <div class="col-sm-12 col-md-8 margin-top-15 no-padding">
                <div class="pull-left margin-top-10 text-center" v-cloak>
                    <md-avatar class="md-large">
                        <img :src="shared.agency.avatar" alt="People">
                    </md-avatar>
                    <sage-image-cropper
                            field="img"
                            @crop-upload-success="cropUploadSuccess"
                            @crop-upload-fail="cropUploadFail"
                            v-model="cropper.show"
                            :width="300"
                            :height="300"
                            :url="uploadUrl"
                            lang-type='en'
                            :params="cropper.params"
                            img-format="png">
                    </sage-image-cropper>
                    <img :src="cropper.imgDataUrl">
                    <br/>
                    <md-button @click="toggleCropper" class="md-transparent font-size-10 margin-left-5">Edit Logo</md-button>
                </div>
                <h4 v-if="!shared.agency.id">Create a New Agency</h4>
                <h4 v-else>{{ shared.agency.name }}</h4>
                <a v-if="shared.agency.id" class="link-primary" :href="agencyUrl" target="_blank">View Your Agency</a>
                <p v-if="!shared.agency.id" class="font-size-12">Agencies allow you to recruit other freelancers to group your skills and take on larger projects.</p>
                <p v-else>Manage your agency details here. Add new freelancers, upload files, change your logo and update your agency description.</p>
            </div>
            <div class="col-sm-12 col-md-4 margin-top-15 no-padding">
                <div class="pull-right">
                    <ui-snackbar-container
                            ref="notifyContainer"
                            :position="notifyPosition"
                            :queue-snackbars="queueNotifications"
                            :allowHtml="allowHtmlNotifications"
                    ></ui-snackbar-container>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="md-tabs md-transparent margin-top-20 md-theme-default md-dynamic-height">
                <nav class="md-whiteframe md-tabs-navigation md-whiteframe-0dp md-has-label">
                    <button type="button" class="md-tab-header" @click="selectTab('details')" :class="getTabNavClass('details')">
                        <div class="md-tab-header-container"><!----> <span>Agency Details</span> <!----></div>
                    </button>
                    <button type="button" class="md-tab-header" @click="selectTab('members')" :class="getTabNavClass('members')"
                            :disabled="!shared.agency.id">
                        <div class="md-tab-header-container"><!----> <span>Agency Members</span> <!----></div>
                    </button>
                    <button type="button" class="md-tab-header" @click="selectTab('jobs');" :class="getTabNavClass('jobs')"
                            :disabled="!shared.agency.id">
                        <div class="md-tab-header-container"><!----> <span>Agency Jobs</span> <!----></div>
                    </button>
                    <button type="button" class="md-tab-header" @click="selectTab('files')" :class="getTabNavClass('files')"
                            :disabled="!shared.agency.id">
                        <div class="md-tab-header-container"><!----> <span>Agency Files</span> <!----></div>
                    </button>
                    <button type="button" class="md-tab-header" @click="selectTab('notifications')" :class="getTabNavClass('notifications')"
                            :disabled="!shared.agency.id">
                        <div class="md-tab-header-container"><!----> <span>Agency Notifications</span> <!----></div>
                    </button>
                    <button type="button" class="md-tab-header" @click="selectTab('links')" :class="getTabNavClass('links')"
                            :disabled="!shared.agency.id">
                        <div class="md-tab-header-container"><!----> <span>Agency Links</span> <!----></div>
                    </button>
                </nav>
            </div>

            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'details'">
                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.name }">
                    <label>Agency Name</label>
                    <md-input required type="text" v-model="shared.agency.name"></md-input>
                    <span v-if="errors.name" class="md-error">{{ errors.name[0] }}</span>
                </md-input-container>

                <md-input-container>
                    <label>Lead Freelalancer Name (Agency Owner)</label>
                    <md-input readonly type="text" v-model="shared.agency.owner.name"></md-input>
                </md-input-container>

                <md-input-container>
                    <label>Location</label>
                    <md-input readonly type="text" v-model="shared.agency.location"></md-input>
                </md-input-container>

                <label class="text-muted font-size-16 font-weight-500 margin-top-30"
                       v-bind:class="{ 'text-invalid' : errors.description  }">Agency Service Desription / Summary *</label>
                <span v-if="errors.description" class="text-invalid"> <br/>{{ errors.description[0] }}</span>
                <sage-editor
                        ref-id="descriptionQuill"
                        :editor-content="description"
                        :editor-toolbar="$root.editorToolbar"
                        @editor-updated="updateDescription"
                        :use-save-button="false">
                </sage-editor>

                <label class="text-muted font-size-16 font-weight-500 margin-top-30"
                       v-bind:class="{ 'text-invalid' : errors.company_description  }">Agency Company Description</label>
                <span v-if="errors.company_description" class="text-invalid"> <br/>{{ errors.company_description[0] }}</span>
                <sage-editor
                        ref-id="companyDescriptionQuill"
                        :editor-content="company_description"
                        :editor-toolbar="$root.editorToolbar"
                        @editor-updated="updateCompanyDescription"
                        :use-save-button="false">
                </sage-editor>

                <md-button v-if="!shared.agency.id" class="md-primary md-raised pull-right margin-top-10" @click="saveAgency()">
                    <md-icon>keyboard_arrow_right</md-icon>
                    Continue
                </md-button>

                <md-button v-if="shared.agency.id" class="md-primary md-raised pull-right margin-top-10" @click="saveAgency()">
                    <md-icon>assignment</md-icon>
                    Update Agency Details
                </md-button>

            </div>

            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'members'">

                <div class="col-md-8 col-sm-12 margin-top-20">
                    <table class="table table-striped  table-condensed">
                        <thead>
                            <th class="text-center">Photo</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Options</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <img src="https://placeimg.com/40/40/people/1" class="img-thumbnail"
                                         :alt="shared.agency.owner.name">

                                </td>
                                <td class="text-center">
                                    {{ shared.agency.owner.name }}
                                    <br/>
                                    <label class="label label-primary">Agency Leader</label>
                                </td>
                                <td class="text-center">
                                    {{ shared.agency.owner.city }}, {{ shared.agency.owner.country }}
                                </td>
                                <td class="text-center">
                                    {{ shared.agency.owner.tagline }}
                                </td>
                                <td class="text-center">
                                    <md-button class="md-icon-button md-primary" @click="viewUser(freelancer)">
                                        <md-icon>account_box</md-icon>
                                    </md-button>
                                    <md-button class="md-icon-button md-primary" disabled>
                                        <md-icon>delete</md-icon>
                                    </md-button>
                                </td>
                            </tr>
                            <tr v-for="(freelancer, index) in shared.agency.freelancers">
                                <td class="text-center">
                                    <img src="https://placeimg.com/40/40/people/1" class="img-thumbnail" :alt="freelancer.name">
                                </td>
                                <td class="text-center">
                                    {{ freelancer.name }}
                                    <br/>
                                    <label class="label label-primary">Member</label>
                                </td>
                                <td class="text-center">
                                    {{ freelancer.city }}, {{ freelancer.country }}
                                </td>
                                <td class="text-center">
                                    {{ freelancer.tagline }}
                                </td>
                                <td class="text-center">
                                    <md-button class="md-icon-button md-primary" @click="viewUser(freelancer)">
                                        <md-icon>account_box</md-icon>
                                    </md-button>
                                    <md-button class="md-icon-button md-primary" @click="confirmDeleteUser(freelancer)">
                                        <md-icon>delete</md-icon>
                                    </md-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-4 col-sm-12  margin-top-20">
                    <md-list>
                        <md-list-item @click="openModal('changeLeaderModal')">
                            <md-icon class="md-primary">compare_arrows</md-icon>
                            <span class="md-primary">Change Leader</span>
                        </md-list-item>

                        <md-list-item @click="openModal('freelancerSearchModal')">
                            <md-icon class="md-primary">assignment_ind</md-icon>
                            <span class="md-primary">Add Freelancer</span>
                        </md-list-item>

                        <md-list-item @click="$root.emitEvent('openFullModal','agencyFreelancerSearchModal')">
                            <md-icon class="md-primary">search</md-icon>
                            <span class="md-primary">Search For Freelancers</span>
                        </md-list-item>
                    </md-list>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'jobs'">
                <sage-agency-jobs ref="agencyJobs" :agency-id="shared.agency.id"></sage-agency-jobs>
            </div>
            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'files'">
                <div class="clearfix"></div>

                <md-list v-if="shared.agency.files.length > 0" class="custom-list md-double-line">
                    <md-list-item v-for="(file, fileIndex) in shared.agency.files" :key="fileIndex">

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
                        id="agencyFiles"
                        v-bind:thumbnailHeight="120"
                        v-bind:thumbnailWidth="120"
                        v-bind:showRemoveLink="true"
                        :maxFileSizeInMB="20"
                        url="/apiv1/files/create"
                        acceptedFileTypes="image/*,application/pdf,.psd,.doc*,.odf"
                        v-on:vdropzone-success="fileSuccess"
                ></sage-dropzone>
            </div>
            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'notifications'">
                <sage-notifications v-if="shared.agency.id" :owner="shared.agency.id" :agency="shared.agency.id" v-cloak></sage-notifications>
            </div>
            <div class="no-padding-sm no-padding-xs" v-if="currentTab == 'links'">
                <sage-edit-links :agency="shared.agency.id"></sage-edit-links>
            </div>

            <div class="clearfix"></div>

        </div>
        <div class="clearfix"></div>

        <!-- Media Editing Modal -->
        <ui-modal ref="fileModal" transition="ui-modal-fade">
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

        <!-- Change Leader Modal -->
        <ui-modal ref="changeLeaderModal" transition="ui-modal-fade">
            <div slot="header">
                Select a New Leader
            </div>

            <md-input-container v-if="shared.agency.freelancers.length > 0">
                <md-select
                        required name="agency_leader"
                        id="agency_leader"
                        v-model="newOwner"
                        placeholder="Select a new leader for this agency."
                >
                    <md-option v-for="(user,id) in shared.agency.freelancers"
                               :value="user.id" :key="id">{{ user.name }}
                    </md-option>
                </md-select>
            </md-input-container>
            <div v-else class="text-center margin-10">
                <label>No eligible users. <br/>Please add freelancers to your agency first.</label>
            </div>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('changeLeaderModal')">Close</md-button>
                <md-button class="md-primary" @click="$refs.changeLeaderConfirm.open();">Save</md-button>
            </div>
        </ui-modal>

        <!-- Freelancer Search Modal -->
        <ui-modal ref="freelancerSearchModal" transition="ui-modal-fade">
            <div slot="header">
                Invite a Freelancer
            </div>

            <md-input-container>
                <md-input :value="freelancerSearchOptions.search" @change="onSearch" placeholder="Search by name or email."></md-input>
            </md-input-container>

            <md-list class="height-400 md-triple-line">
                <md-list-item v-for="(freelancer, index) in freelancers" :key="index">

                    <img :src="freelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;">

                    <div class="md-list-text-container margin-top-10 margin-left-20 padding-bottom-5">
                        <div>
                            {{ freelancer.name }}
                        </div>
                        <span>Joined on {{ freelancer.created_at }}</span>
                        <p>{{ freelancer.bio  | limitTo(140) }}</p>
                    </div>

                    <md-button class="md-primary" @click="inviteFreelancerConfirm(freelancer)">
                        Invite
                    </md-button>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
                <md-list-item v-if="freelancers.length === 0">
                        <div class="md-list-text-container">
                            No freelancers found, enter a search term above to refine your results.
                        </div>
                </md-list-item>
            </md-list>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('freelancerSearchModal')">Close</md-button>
            </div>
        </ui-modal>

        <!-- Add/Edit Link -->
        <ui-modal ref="saveLinkModal" transition="ui-modal-fade">
            <div slot="header">
                Add / Edit Link <span v-if="shared.link.id">({{ shared.link.title }}</span>
            </div>

            <md-input-container v-bind:class="{ 'md-input-invalid' : shared.errors.url  }">
                <label>Link URL</label>
                <md-input type="text" name='url' v-model="shared.link.url" required placeholder="Enter the URL for your link." @focus.native="$event.target.select()"></md-input>
                <span v-if="shared.errors.url" class="md-error">{{ shared.errors.url[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : shared.errors.title  }">
                <label>Link Title</label>
                <md-input type="text" name='url' v-model="shared.link.title" required placeholder="Enter the title for your link." @focus.native="$event.target.select()"></md-input>
                <span v-if="shared.errors.title" class="md-error">{{ shared.errors.title[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : shared.errors.description  }">
                <label>Link Description (Optional)</label>
                <md-input type="text" name='url' v-model="shared.link.description" required placeholder="Enter a short description of this link." @focus.native="$event.target.select()"></md-input>
                <span v-if="shared.errors.description" class="md-error">{{ shared.errors.description[0] }}</span>
            </md-input-container>

            <div class="md-input-container md-theme-default no-margin-bottom">
                <label>Screenshots / Files</label>
            </div>
            <md-list v-if="shared.link.files.length > 0" class="custom-list md-double-line">
                <md-list-item v-for="(file, fileIndex) in shared.link.files" :key="fileIndex">

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

                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>

            <div class="clearfix"></div>

            <sage-dropzone
                    ref="linkFiles"
                    id="linkFiles"
                    v-bind:thumbnailHeight="120"
                    v-bind:thumbnailWidth="120"
                    v-bind:showRemoveLink="true"
                    :maxFileSizeInMB="20"
                    url="/apiv1/files/create/link"
                    acceptedFileTypes="image/*,application/pdf,.psd,.doc*,.odf"
                    v-on:vdropzone-success="fileSuccessLink"
            ></sage-dropzone>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('saveLinkModal')">Close</md-button>
                <md-button class="md-primary" @click="saveLink()">Save</md-button>
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

        <md-dialog-confirm
                md-title="Delete this file"
                md-content-html="Are you sure you want to remove this file?"
                md-ok-text="Delete File"
                md-cancel-text="Cancel"
                @close="deleteFileLink"
                ref="delete-file-link">
        </md-dialog-confirm>

        <md-dialog-confirm
                md-title="Remove User"
                md-content-html="Are you sure you want to remove this user?"
                md-ok-text="Remove User"
                md-cancel-text="Cancel"
                @close="deleteUser"
                ref="delete-user">
        </md-dialog-confirm>

        <md-dialog-confirm
                md-title="Change Agency Leader"
                md-content-html="Are you sure you want to change the leader of this agency? You will no longer be able to make changes to this agency yourself."
                md-ok-text="Change Leader"
                md-cancel-text="Cancel"
                @close="changeAgencyLeader"
                ref="changeLeaderConfirm">
        </md-dialog-confirm>

        <md-dialog-confirm
                md-title="Invite Freelancer"
                md-content-html="Are you sure you want to invite this freelancer to join your agency?"
                md-ok-text="Send Invite"
                md-cancel-text="Cancel"
                @close="inviteFreelancer"
                ref="inviteFreelancerConfirm">
        </md-dialog-confirm>

    </div>
</template>
<script type="text/babel">

    import {VueEditor} from 'vue2-editor';
    export default {
        mounted() {
            console.log(' Create Agency component ready.')
            var vm = this;
            appBus.$on('openParentModal', function (ref) {
                if (vm.$refs[ref]) {
                    vm.$refs[ref].open();
                }
            });

            appBus.$on('refreshAgency', function () {
                vm.syncAgency();
            });

			appBus.$on('agency:inviteFreelancer', function (freelancer) {
				vm.inviteFreelancerConfirm(freelancer);
			});

            appBus.$on('closeParentModal', function (ref) {
                if (vm.$refs[ref]) {
                    vm.$refs[ref].close();
                }
            });

            if (this.agencyId) {
                this.shared.agency.id = this.agencyId;

                /* Load & sync agency data */
                this.syncAgency(this.shared.agency.id);

                /* Activate notifications tab */
                this.currentTab = 'notifications';
            }

            /* Set Active Tab Based on Hash */
            this.urlHash = window.location.hash.substr(1);
            console.log(this.urlHash);
            switch (this.urlHash) {
                case 'members':
                    this.updateActiveTab(1);
                    break;
            }

            /* Set defaults if agency does not exist yet */
            if (!this.shared.agency.id) {
                this.shared.agency.owner = this.user;
                this.shared.agency.city = this.user.city;
                this.shared.agency.country = this.user.country;
                this.shared.agency.location = this.user.city + ", " + this.user.country;
            }
        },
        props: ['agencyId'],
        components: {
            VueEditor
        },
        computed: {
            isDescriptionValid: function () {
                if (!this.errors.description) {
                    return false;
                }
                if (this.errors.description.length > 0) {
                    return true;
                }
                return false;
            },
            agencyUrl: function() {
                return '/agency/' + this.shared.agency.id;
            },
            uploadUrl: function() {
                return '/apiv1/files/agencyAvatar/' + this.shared.agency.id;
            }
        },
        data() {
            return {
                urlHash: '',
                user: window.Laravel.user,
                shared: window.sageSource,
                newOwner: null,
                errors: {},
                activeTab : 0,
                currentTab: 'details',
                freelancers: [],
                selectedFreelancer: {},
                totalFreelancers: 0,
                freelancerSearchOptions: {
                    pager: {
                        page: 1,
                        size: 20
                    },
                    sort: {
                        name: 'created_at',
                        type: 'desc'
                    },
                    search: '',
                    filter: 'filter-freelancers'
                },
                state: {
                    saving: false,
                    step: 1,
                    freelancerSearchLoading: false
                },
                description: '',
                company_description : '',
                selectedFile: {},
                selectedUser: {},
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
        filters: {
            limitTo: function (string, value) {
                if (!string) {
                    return '';
                }

                if (string.length <= value) {
                    return string;
                }

                return string.substring(0, value) + '...';
            }
        },
        methods: {
        	getTabNavClass(tab) {
        		var navClass = '';
        		if (this.currentTab == tab) {
        			navClass += ' md-active ';
                }

                if (tab != 'details' && !this.shared.agency.id) {
        			navClass += ' md-disabled ';
                }

                return navClass;
            },
        	selectTab(tab) {
        	    this.currentTab = tab;
            },
            updateDescription(html) {
                this.shared.agency.description = html;
            },
            updateCompanyDescription(html) {
                this.shared.agency.company_description = html;
            },
            updateActiveTab: function (tabId) {
                this.activeTab = tabId;
            },
            fileSuccess: function (file) {
                console.log(file);
                var response = JSON.parse(file.xhr.response);

                /* Check if the file is an image */
                var isImage = false;
                if (file.type.indexOf('image') !== -1) {
                    var isImage = true;
                }

                this.shared.agency.files.push({
                    name: file.name,
                    size: file.size,
                    is_image: isImage,
                    id: response.id,
                    path: response.path,
                    url: response.url,
                    'user_path': response['user_path'],
                    description: 'No description set.',
                    public: true,
                    featured : false
                })

                this.saveAgency();
            },
            confirmDeleteFile(file) {
                this.selectedFile = file;
                this.$refs['delete-file'].open();
            },
            confirmDeleteUser(user) {
                this.selectedUser = user;
                this.$refs['delete-user'].open();
            },
            /**
             * @todo - Send request to delete file and relation.
             */
            deleteFile(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                var index = this.shared.agency.files.indexOf(this.selectedFile);
                this.shared.agency.files.splice(index, 1);
                this.$root.showNotification('File ' + this.selectedFile.name + ' removed successfully.');
                this.selectedFile = {};
            },
            editFile(file) {
                this.selectedFile = file;
                this.openModal('fileModal');
            },
            saveEditedFile() {
                this.closeModal('fileModal');
                this.saveAgency();
            },
            fileSuccessLink: function (file) {
                console.log(file);
                var response = JSON.parse(file.xhr.response);

                /* Check if the file is an image */
                var isImage = false;
                if (file.type.indexOf('image') !== -1) {
                    var isImage = true;
                }

                this.shared.link.files.push({
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
            confirmDeleteFileLink(file) {
                this.selectedFile = file;
                this.$refs['delete-file-link'].open();
            },
            deleteFileLink(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                var index = this.shared.link.files.indexOf(this.selectedFile);
                this.shared.link.files.splice(index, 1);
                this.$root.showNotification('File ' + this.selectedFile.name + ' removed successfully.');
                this.selectedFile = {};

            },
            saveLink() {
                appBus.$emit('savedLink', this.shared.link);
            },
            openModal(ref) {
                this.$refs[ref].open();
            },
            closeModal(ref) {
                this.$refs[ref].close();
            },
            changeAgencyLeader(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }
                this.closeModal('changeLeaderModal');
                this.updateAgencyOwner();
            },
            viewUser(freelancer) {
              window.location = "/profile/" + freelancer.id;
            },
            deleteUser(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }
                this.state.saving = true;
                var apiUrl = '/apiv1/agencies/deleteUser/' + this.shared.agency.id

                this.$http.post(apiUrl, {'user_id': this.selectedUser.id}).then((response) => {
                    this.state.saving = false;
                    this.$root.showNotification(response.body.message);
                    this.syncAgency();
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    }
                });
            },
            updateAgencyOwner() {
                this.state.saving = true;
                var apiUrl = '/apiv1/agencies/updateOwner/' + this.shared.agency.id

                this.$http.post(apiUrl, {'new_owner_id' : this.newOwner}).then((response) => {
                    this.state.saving = false;
                    this.$root.showNotification(response.body.message);
                    this.syncAgency();
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    }
                });
            },
            syncAgency: function (id) {
                this.$http.get('/apiv1/agencies/' + this.shared.agency.id, this.shared.agency).then((response) => {
                    this.state.saving = false;
                    this.shared.agency = response.body.results;
                    this.description = this.shared.agency.description;
                    this.company_description = this.shared.agency.company_description;
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
            saveAgency() {

                this.errors = {}
                this.state.saving = true;

                if (!this.shared.agency.id) {
                    var apiUrl = '/apiv1/agencies/create/';
                } else {
                    var apiUrl = '/apiv1/agencies/update/' + this.shared.agency.id
                }

                this.$http.post(apiUrl, this.shared.agency).then((response) => {
                    this.state.saving = false;


                    /* Redirect to edit page if this is a new agency */
                    if (!this.shared.agency.id) {
                        window.location = '/agencies/edit/' + response.body.results.id + '?intent=created';
                        return;
                    }

                    /* Update the agency and activate members tab */
					this.$root.showNotification(response.body.message);
                    this.shared.agency = response.body.results;
                    this.activeTab = 1;

                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }
                });


            },
            onSearch(term) {
                this.freelancerSearchOptions.search = term;
                this.searchFreelancers();
            },
            searchFreelancers() {
                this.state.freelancerSearchLoading = true;
                this.freelancerSearchOptions.page = this.freelancerSearchOptions.pager.page;
                this.$http.get('/apiv1/users', {params: this.freelancerSearchOptions}).then((response) => {

                    this.freelancers = response.body.data;
                    this.freelancerSearchOptions.pager.page = response.body['current_page'];
                    this.totalFreelancers = response.body.total;
                    this.state.freelancerSearchLoading = false;

                }, (response) => {
                    console.log("Error loading users");
                    console.log(response);
                });
            },
            inviteFreelancerConfirm(freelancer) {
                this.selectedFreelancer = freelancer;
                this.$refs.inviteFreelancerConfirm.open();
            },
            inviteFreelancer(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                this.state.saving = true;
                var apiUrl = '/apiv1/agencies/invite/' + this.shared.agency.id

                this.$http.post(apiUrl, {'user_id': this.selectedFreelancer.id}).then((response) => {
                    this.state.saving = false;
                    this.$root.showNotification(response.body.message);
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    }
                });
            },
            /* Cropper */
            toggleCropper() {
                this.cropper.show = !this.cropper.show;
            },
            cropUploadSuccess(jsonData, field){
                this.shared.agency.avatar = jsonData.url;
                this.cropper.show = false;
                this.$root.showNotification("Your logo has been updated.");

            },
            cropUploadFail(status, field){
                this.$root.showNotification("There was an error uploading your logo, please try again.");
            },
            showNotification(message) {
                this.$root.$refs.mainNotifyContainer.createSnackbar({
                    message: message,
                    action: '',
                    actionColor: 'accent',
                    duration: 5000
                });
            },
        }
    }
</script>
