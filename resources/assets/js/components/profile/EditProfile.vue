<!--

Usage: <sage-login> </sage-login>
Properties: redirect-url [Where to redirect after a successful login]

-->

<template>
    <div>
        <sage-image-cropper
                field="img"
                @crop-upload-success="cropUploadSuccess"
                @crop-upload-fail="cropUploadFail"
                v-model="cropper.show"
                :width="300"
                :height="300"
                url="/apiv1/files/avatar/"
                lang-type='en'
                :params="cropper.params"
                img-format="png">
        </sage-image-cropper>
        <img :src="cropper.imgDataUrl">
        <md-tabs md-fixed class="md-transparent margin-top-20">

            <md-tab id="profile_details" md-label="Profile Bio" @change="updateActiveTab" :md-active="activeTab == 0" >

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.email  }">
                    <label>Email (Not Public)</label>
                    <md-input required type="text" v-model="shared.profile.email"></md-input>
                    <span v-if="errors.email" class="md-error">{{ errors.email[0] }}</span>
                </md-input-container>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.name  }">
                    <label>Name</label>
                    <md-input required type="text" v-model="shared.profile.name" maxlength="35"></md-input>
                    <span v-if="errors.name" class="md-error">{{ errors.name[0] }}</span>
                </md-input-container>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.tagline  }">
                    <label>Tag Line</label>
                    <md-input required type="text" v-model="shared.profile.tagline" maxlength="60" @focus.native="$event.target.select()"></md-input>
                    <span v-if="errors.tagline" class="md-error">{{ errors.tagline[0] }}</span>
                </md-input-container>

                <ui-autocomplete
                        :autofocus="false"
                        :limit="8"
                        label="Country *"
                        name="country"
                        :filter="autoCompleteMatch"
                        :invalid="errors.country ? true : false"
                        :error="errors.country ? errors.country[0] : null"
                        :suggestions="countrySelect"
                        placeholder="Start typing..."
                        :minChars="1"
                        value=""
                        v-model="shared.profile.country"
                        @select="updateCitySelect"
                ></ui-autocomplete>

                <!--
                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.country }">
                    <label>Country</label>
                    <md-select required name="country" id="country" v-model="shared.profile.country" @selected="updateCitySelect">
                        <md-option v-for="country in countries.countries" :value="country.name">{{ country.name }}</md-option>
                    </md-select>
                    <span v-if="errors.country" class="md-error">{{ errors.country[0] }}</span>
                </md-input-container>
                -->

                <ui-autocomplete
                        :autofocus="false"
                        :limit="8"
                        label="City *"
                        name="city"
                        :invalid="errors.city ? true : false"
                        :error="errors.city ? errors.city[0] : null"
                        :suggestions="cities"
                        :filter="autoCompleteMatch"
                        placeholder="Start typing..."
                        :minChars="1"
                        value=""
                        v-model="shared.profile.city"
                ></ui-autocomplete>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.phone  }">
                    <label>Phone Number (Not Public)</label>
                    <md-input type="text" v-model="shared.profile.phone" maxlength="35" @focus.native="$event.target.select()"></md-input>
                    <span v-if="errors.phone" class="md-error">{{ errors.phone[0] }}</span>
                </md-input-container>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.company  }">
                    <label>Company Name</label>
                    <md-input type="text" v-model="shared.profile.company" maxlength="40" @focus.native="$event.target.select()"></md-input>
                    <span v-if="errors.company" class="md-error">{{ errors.company[0] }}</span>
                </md-input-container>

                <md-input-container v-bind:class="{ 'md-input-invalid' : errors.company_description  }">
                    <label>Company Description</label>
                    <md-input type="text" v-model="shared.profile.company_description" maxlength="60" @focus.native="$event.target.select()"></md-input>
                    <span v-if="errors.company_description" class="md-error">{{ errors.company_description[0] }}</span>
                </md-input-container>

                <label class="text-muted font-size-16 font-weight-500 margin-top-30">
                    Personal Bio (Share your skills, experience and information about yourself)
                </label>
                <vue-editor
                        id="profileBioText"
                        :editorToolbar="$root.editorToolbar"
                        v-model="shared.profile.bio"></vue-editor>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-right no-margin-right" @click="saveProfile()">
                    <md-icon>account_box</md-icon>
                    Save Your Profile
                </md-button>

            </md-tab >

            <md-tab id="profile_media" v-if="shared.profile.is_freelancer" @change="updateActiveTab" :md-active="activeTab == 1" md-label="Media & Files">

                <sage-edit-media user-type="user"></sage-edit-media>

            </md-tab>

            <md-tab id="profile_links" v-if="shared.profile.is_freelancer" @change="updateActiveTab" :md-active="activeTab == 2" md-label="Links">

                <sage-edit-links></sage-edit-links>

            </md-tab>

            <md-tab v-if="shared.profile.is_freelancer" id="profile_skills" @change="updateActiveTab" :md-active="activeTab == 3" md-label="Skills">
                <sage-profile-edit-skills></sage-profile-edit-skills>
            </md-tab>

            <md-tab v-if="shared.profile.is_freelancer" id="profile_references" @change="updateActiveTab" :md-active="activeTab == 4" md-label="References & Recommendations">
                <sage-profile-edit-references></sage-profile-edit-references>
            </md-tab>

        </md-tabs>

        <!-- Save Skill Modal -->
        <ui-modal ref="saveSkillModal" transition="ui-modal-fade" @open="suggestions = []; updateAverageRate(shared.skill.level);">
            <div slot="header">
                Add a New Skill ({{ shared.skill.name }})
            </div>


            <md-input-container v-bind:class="{ 'md-input-invalid' : errors.level  }">
                <label>Experience Level</label>
                <md-select
                        required
                        name='level'
                        v-model="shared.skill.level"
                        @input="updateAverageRate"
                        placeholder="Select your experience level"
                >
                    <md-option v-for="(level,id) in shared.skill.avg_rates"
                               :value="level.level" v-if="level.level != 'Total'" :key="id">{{ level.level }}
                    </md-option>
                </md-select>
                <span v-if="errors.level" class="md-error">{{ errors.level[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : errors.rate  }">
                <label>Hourly Rate <span v-if="avgRate">(Average rate for "{{ shared.skill.level }}"  level is ${{ avgRate }}.00/hr)</span></label>
                <md-input type="number" name='rate' v-model="shared.skill.rate" required placeholder="Enter your hourly rate."></md-input>
                <span v-if="errors.rate" class="md-error">{{ errors.rate[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : errors.experience  }">
                <label>Years of Experience</label>
                <md-input type="number" name='experience' v-model="shared.skill.experience" required placeholder="Enter years of experience for this skill."></md-input>
                <span v-if="errors.experience" class="md-error">{{ errors.experience[0] }}</span>
            </md-input-container>

            <div v-if="suggestions.length > 0" class="margin-top-30">
                    <h4 class="text-muted">Suggestions For This Skill</h4>
                    <div v-for="(suggestion,index) in suggestions" class="text-muted">
                        <md-icon class="margin-right-20">announcement</md-icon> {{ suggestion }}
                        <hr/>
                    </div>

            </div>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('saveSkillModal')">Close</md-button>
                <md-button class="md-primary" @click="saveSkill">Save</md-button>
            </div>
        </ui-modal>

        <!-- Freelancer Search Modal -->
        <ui-modal ref="freelancerSearchModal" transition="ui-modal-fade">
            <div slot="header">
                Add a Recommended Freelancer
            </div>

            <md-input-container>
                <md-input :value="freelancerSearchOptions.search" @change="onSearch" placeholder="Search by name or email."></md-input>
            </md-input-container>

            <md-list v-if="!selectedFreelancer.id" class="height-400 md-triple-line">
                <md-list-item v-for="(freelancer, index) in freelancers" :key="index">
                    <img :src="freelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;">

                    <div class="md-list-text-container margin-top-10 margin-left-20 padding-bottom-5">
                        <div>
                            {{ freelancer.name }}
                        </div>
                        <span>Joined on {{ freelancer.created_at }}</span>
                        <p>{{ freelancer.bio | limitTo(140) }}</p>
                    </div>

                    <md-button class="md-primary" @click="selectedFreelancer = freelancer">
                        Add
                    </md-button>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
                <md-list-item v-if="freelancers.length === 0">
                    <div class="md-list-text-container">
                        Search by username or email to find and add recommended freelancers to your profile.
                    </div>
                </md-list-item>
            </md-list>

            <div v-else>
                <h4 class="pull-left">Add Recommendation For {{ selectedFreelancer.name}}</h4>
                <md-button class="pull-right" @click="selectedFreelancer = {}" md-condensed>
                    <md-icon>close</md-icon>
                </md-button>
                <div class="clearfix"></div>
                <md-list class="md-triple-line">
                    <md-list-item>
                        <img :src="selectedFreelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;">

                        <div class="md-list-text-container margin-top-10 margin-left-20 padding-bottom-5">
                            <div>
                                {{ selectedFreelancer.name }}
                            </div>
                            <span>Joined on {{ selectedFreelancer.created_at }}</span>
                            <p>{{ selectedFreelancer.bio | limitTo(140) }}</p>
                        </div>
                        <md-divider class="md-inset"></md-divider>
                    </md-list-item>
                </md-list>
                <md-input-container>
                    <md-input class="padding-left-15 padding-right-15" v-model="recommendationSnippet" placeholder="Additional information about this recommendation. (optional)"></md-input>
                </md-input-container>

                <md-button class="md-primary pull-right md-raised margin-bottom-20" @click="addRecommendation()">
                    Save Recommendation
                </md-button>

            </div>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('freelancerSearchModal')">Close</md-button>
            </div>
        </ui-modal>

        <!-- Freelancer Search Modal -->
        <ui-modal ref="askForRecommendationModal" transition="ui-modal-fade">
            <div slot="header">
                Ask For a Recommendation
            </div>

            <md-input-container>
                <md-input :value="freelancerSearchOptions.search" @change="onSearch" placeholder="Search by name or email."></md-input>
            </md-input-container>

            <md-list v-if="!selectedFreelancer.id" class="height-400 md-triple-line">
                <md-list-item v-for="(freelancer, index) in freelancers" :key="index">
                    <img :src="freelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;">

                    <div class="md-list-text-container margin-top-10 margin-left-20 padding-bottom-5">
                        <div>
                            {{ freelancer.name }}
                        </div>
                        <span>Joined on {{ freelancer.created_at }}</span>
                        <p>{{ freelancer.bio | limitTo(140) }}</p>
                    </div>

                    <md-button class="md-primary" @click="selectedFreelancer = freelancer">
                        Ask User
                    </md-button>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
                <md-list-item v-if="freelancers.length === 0">
                    <div class="md-list-text-container">
                        Search by username or email to find and add recommended freelancers to your profile.
                    </div>
                </md-list-item>
            </md-list>

            <div v-else>
                <h4 class="pull-left">Ask For Recommendation From {{ selectedFreelancer.name}}</h4>
                <md-button class="pull-right" @click="selectedFreelancer = {}" md-condensed>
                    <md-icon>close</md-icon>
                </md-button>
                <div class="clearfix"></div>
                <md-list class="md-triple-line">
                    <md-list-item>
                        <img :src="selectedFreelancer.avatar" class="img-thumbnail margin-10" style="width: 70px; height: 70px;">

                        <div class="md-list-text-container margin-top-10 margin-left-20 padding-bottom-5">
                            <div>
                                {{ selectedFreelancer.name }}
                            </div>
                            <span>Joined on {{ selectedFreelancer.created_at }}</span>
                            <p>{{ selectedFreelancer.bio | limitTo(140) }}</p>
                        </div>
                        <md-divider class="md-inset"></md-divider>
                    </md-list-item>
                </md-list>
                <md-input-container>
                    <md-input class="padding-left-15 padding-right-15" v-model="askForRecommendationSnippet" placeholder="Your message to the user.."></md-input>
                </md-input-container>

                <md-button class="md-primary pull-right md-raised margin-bottom-20" @click="askForRecommendation()">
                    Send Recommendation Request
                </md-button>

            </div>

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
                <md-input type="text" name='url' v-model="shared.link.description" placeholder="Enter a short description of this link." @focus.native="$event.target.select()"></md-input>
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
                    v-on:vdropzone-success="fileSuccess"
            ></sage-dropzone>

            <div slot="footer">
                <md-button class="md-primary" @click="closeModal('saveLinkModal')">Close</md-button>
                <md-button class="md-primary" @click="saveLink()">Save</md-button>
            </div>
        </ui-modal>

        <!-- Media Editing Modal -->
        <ui-modal ref="fileModal" transition="ui-modal-fade">
            <div slot="header">
                Edit {{ shared.file.name }}
            </div>

            <md-input-container>
                <label>File Name</label>
                <md-input type="text" v-model="shared.file.name" :maxlength="60" @focus.native="$event.target.select(); $event.target.value='';"></md-input>
            </md-input-container>

            <md-input-container>
                <label>File Description</label>
                <md-input type="text" v-model="shared.file.description" :maxlength="60" @focus.native="$event.target.select(); $event.target.value='';"></md-input>
            </md-input-container>

            <md-switch v-model="shared.file.public" id="public" name="public" class="md-primary">Display on Public Profile</md-switch>

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
            console.log(' Edit profile component ready.')
            var vm = this;
            appBus.$on('openParentModal', function (ref) {
                if (vm.$refs[ref]) {
                    vm.$refs[ref].open();
                }
            });

			appBus.$on('editAvatar', function (ref) {
        console.log(ref);
				vm.toggleCropper();
			});

            appBus.$on('closeParentModal', function (ref) {
                if (vm.$refs[ref]) {
                    vm.$refs[ref].close();
                }
            });

            appBus.$on('refreshProfile', function () {
                vm.refreshProfile();
            });

            appBus.$off('savedFile');
			appBus.$on('savedFile', function (event) {
				vm.saveProfile();
			});

            /* Set Active Tab Based on Hash */
            this.urlHash = window.location.hash.substr(1);
            console.log(this.urlHash);
            switch (this.urlHash) {
                case 'links':
                    this.updateActiveTab(2);
                    break;
            }

            /* Load countries and cities */
			this.$http.get('/js/countries.json').then((response) => {
				this.countries = response.body;
			}, (response) => {
				console.log("Error loading countries");
			});

            this.refreshProfile();

            setTimeout(function() {
                $(".sageImage").fancybox();
            },3000);

        },
        components: {
            VueEditor
        },
        props: ['profileId'],
        data() {
            return {
                urlHash: '',
                activeTab: 0,
                shared: window.sageSource,
                avgRate: null,
                shareEmail: '',
                selectedFile : null,
                userBio: '',
                errors: {},
                suggestions: [],
                state: {
                    saving: false,
                    freelancerSearchLoading: false
                },
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
                freelancers: [],
                selectedFreelancer: {},
                totalFreelancers: 0,
                recommendationSnippet: '',
                askForRecommendationSnippet: 'Hey, can you please add me as a recommended freelancer to your profile. I would appreciate it, thank you!',
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
                countries: [],
                cities: []
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
        computed: {
            countrySelect() {
            	var countries = [];
            	if (!this.countries.countries) {
            		return countries;
                }

            	for (var key in this.countries.countries) {
					countries.push({
						label: this.countries.countries[key].name,
						value: this.countries.countries[key].name
					});
				}

            	return countries;
            }
        },
        methods: {
        	autoCompleteMatch: function(suggestion,query) {
        	    return suggestion.value.toLowerCase().indexOf(query.toLowerCase()) !== -1;
            },
            updateActiveTab: function (tabId) {
                this.activeTab = tabId;
            },
            updateBio(html) {
                this.shared.profile.bio = html;
            },
            updateCitySelect() {
                let countryCodes = Object.keys(this.countries.countries);
                let countryCode = null;
                let vm = this;
            	countryCode = _.find(countryCodes, function(country) {
            		return vm.countries.countries[country].name == vm.shared.profile.country;
                });

                /* Load countries and cities */
				this.$http.get('/apiv1/cities', {params: {
					countryCode: countryCode,
                    country: vm.countries.countries[countryCode].name,
                    format: 'autocomplete'
				}}).then((response) => {
					this.cities = response.body;
				}, (response) => {
					console.log("Error loading cities");
				});
            },
            updateAverageRate: function (value) {
                var vm = this;
                var rate = null;
                _.each(this.shared.skill.avg_rates, function (item) {
                    if (item.level == vm.shared.skill.level) {
                        rate = item.rate;
                    }
                });

                /* Auto fill rate if none has been entered */
                if (!_.isNull(rate) && _.isNull(this.shared.skill.rate)) {
                    this.shared.skill.rate = rate;
                }

                this.avgRate = Math.round(rate);
            },
            askForRecommendation() {
        		var message =  {
                        from_user_id : this.shared.user.id,
                        to_user_id: this.selectedFreelancer.id,
                        owner_id: this.shared.user.id,
                        title: 'Recommendation Request',
                        message: this.askForRecommendationSnippet,
                        nice_date: 'just now',
                        type: 'user-message',
                        sender: window.sageSource.user
                    }

				this.$http.post('/apiv1/notifications/sendMessage', message).then((response) => {
					this.$root.showNotification('Your recommendation request has been sent.');
				}, (response) => {
					this.state.sending = false;
					this.$root.showNotification(response.body.message);
					console.log("Error sending message.");
					console.log(response);
				});

            },
            addRecommendation() {
            	console.log('adding recommendation');
                appBus.$emit('addedRecommendation', this.selectedFreelancer, this.recommendationSnippet);
            },
            saveLink() {
                appBus.$emit('savedLink', this.shared.link);
            },
            fileSuccess: function (file) {
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
            confirmDeleteFile(file) {
                this.selectedFile = file;
                this.$refs['delete-file'].open();
            },
            deleteFile(confirmation) {
                if (confirmation !== 'ok') {
                    return;
                }

                var index = this.shared.link.files.indexOf(this.selectedFile);
                this.shared.link.files.splice(index, 1);
                this.$root.showNotification('File ' + this.selectedFile.name + ' removed successfully.');
                this.selectedFile = {};

            },
            editFile(file) {
                this.selectedFile = file;
                this.openModal('fileModal');
            },
            saveEditedFile() {
            	appBus.$emit('savedEditedFile');
                this.closeModal('fileModal');
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
            saveSkill() {
                if (!this.shared.skill.name || this.shared.skill.name.length < 3) {
                    this.$root.showNotification("Skill names must be at least 3 characters or longer.");
                    return;
                }

                appBus.$emit('saveSkill', this.shared.skill);

                this.errors = {}
                var apiUrl = '/apiv1/skills/addUserSkill/';

                this.$http.post(apiUrl, this.shared.skill).then((response) => {
                    this.$root.showNotification(response.body.message);

                    if (response.body.suggestions) {
                        this.suggestions = response.body.suggestions;
                    } else {
                        this.suggestions = [];
                    }

                    if (this.suggestions.length == 0) {
						this.closeModal('saveSkillModal');
                    }

                    appBus.$emit('savedSkill', this.shared.skill);

                }, (response) => {
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }

                    if (response.body.suggestions) {
                        this.suggestions = response.body.suggestions;
                    } else {
                        this.suggestions = [];
                    }
                });

            },
            saveProfile() {

                this.errors = {}
                this.state.saving = true;
                this.$http.post('/apiv1/profile/update/' + this.shared.profile.id, this.shared.profile).then((response) => {

                    this.state.saving = false;
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
            refreshProfile() {
                this.$http.get('/apiv1/profile/' + this.profileId).then((response) => {
                    this.shared.profile = response.body.user;
                    if (!_.isUndefined(this.shared.profile.bio)) {
                        this.userBio = this.shared.profile.bio;
                    }
                }, (response) => {
                    this.$root.showNotification(response.body.message);
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
            openRootDialog(ref) {
                this.$root.openDialog(ref);
            },
            openDialog(ref) {
                this.$refs[ref].open();
            },
            /* Cropper */
            toggleCropper() {
                this.cropper.show = !this.cropper.show;
            },
            cropUploadSuccess(jsonData, field){
                this.shared.profile.avatar = jsonData.url;
                this.cropper.show = false;
                this.$root.showNotification("Your avatar has been updated.")

            },
            cropUploadFail(status, field){
                this.$root.showNotification("There was an error uploading your avatar, please try again.")
            },
            openModal(ref) {
                this.$refs[ref].open();
            },
            closeModal(ref) {
                this.$refs[ref].close();
            },

        }
    }
</script>
