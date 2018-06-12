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
                url="/apiv1/files/avatar"
                lang-type='en'
                :params="cropper.params"
                img-format="png">
        </sage-image-cropper>
        <div class="profile-wizard">

            <div class="wizard-step" v-if="wizard.step == 'profile'">

                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon> Please take a minute to fill out your profile details. Having a complete profile is important for both clients & freelancers to get the most out of our platform.</p>
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
                        @focus.native="$event.target.select()"
                        :autofocus="false"
                        :limit="8"
                        label="Country *"
                        name="country"
                        :invalid="errors.country ? true : false"
                        :error="errors.country ? errors.country[0] : null"
                        :suggestions="countrySelect"
                        :filter="autoCompleteMatch"
                        placeholder="Start typing..."
                        :minChars="1"
                        value=""
                        v-model="shared.profile.country"
                        @select="updateCitySelect"
                ></ui-autocomplete>

                <ui-autocomplete
                        @focus.native="$event.target.select()"
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
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='profile'" disabled>
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button v-if="shared.user.is_freelancer"
                           class="md-primary md-raised pull-right no-margin-right margin-top-20"
                           @click="saveProfile('avatar')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <md-button v-else
                           class="md-primary md-raised pull-right no-margin-right margin-top-20"
                           @click="saveProfile('avatar')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>


            </div >

            <div class="wizard-step" v-if="wizard.step == 'avatar'">
                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    Upload a profile picture of your face. The image be should clear and not include any other people.
                </p>
                <div class="text-center">
                    <md-avatar class="md-x-large">
                        <img :src="shared.profile.avatar" :alt="shared.profile.name">
                    </md-avatar><br/>
                    <md-button @click="$root.emitEvent('editAvatar')" class="md-primary margin-left-5"><md-icon>add</md-icon> Upload Profile Photo</md-button>
                </div>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='profile'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button v-if="shared.profile.is_freelancer"
                           class="md-primary md-raised pull-right no-margin-right margin-top-20"
                           @click="saveProfile('skills')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <md-button v-else
                           class="md-primary md-raised pull-right no-margin-right margin-top-20"
                           @click="saveProfile('complete')">
                    Finish
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20" v-if="shared.user.is_freelancer">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='skills'">Skip This Step (Not Recommended)</a>
                </div>
            </div>

            <div class="wizard-step" v-if="wizard.step == 'media'">

                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    If you have any images or files you would like to share (portfolio items, documents etc.) you can upload them here and add a relevant description. Having a portfolio that includes samples of your work is important to attract high quality clients.
                </p>

                <sage-edit-media user-type="user"></sage-edit-media>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='profile'" disabled>
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button class="md-primary md-raised pull-right no-margin-right margin-top-20" @click="saveProfile('references')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='references'">Skip This Step (Not Recommended)</a>
                </div>

            </div>

            <div class="wizard-step" v-if="wizard.step == 'links'">

                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                     Include links clients may find interesting here. You might include links to previously completed projects, social media profiles, your CV or other related pages.
                </p>


                <sage-edit-links></sage-edit-links>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='skills'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button
                        class="md-primary md-raised pull-right no-margin-right margin-top-20"
                        @click="saveProfile('media')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='media'">Skip This Step (Not Recommended)</a>
                </div>

            </div >

            <div class="wizard-step" v-if="wizard.step == 'skills'">

                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    Adding skills to your profile essental. We recommend adding at least 5 skills to your profile. By adding skills, you help clients find your profile for related jobs.</p>
                <sage-profile-edit-skills></sage-profile-edit-skills>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='avatar'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button
                           class="md-primary md-raised pull-right no-margin-right margin-top-20"
                           @click="saveProfile('links')">
                    Continue
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='links'">Skip This Step (Not Recommended)</a>
                </div>
            </div>

            <div class="wizard-step" v-if="wizard.step == 'references'">
                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    Add references to your profile. References can include past jobs, references from other freelancing platforms or personal references.
                </p>

                <p class="text-muted margin-top-5 tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    Add other freelancers you know on SageGroupy to your profile as a "Recommended Freelancer" or ask other freelancers to recommend you on their profile.
                </p>


                <sage-profile-edit-references></sage-profile-edit-references>

                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='media'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>
                <md-button
                        class="md-primary md-raised pull-right no-margin-right margin-top-20"
                        @click="saveProfile('share')">
                    Next
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='share'">Skip This Step (Not Recommended)</a>
                </div>
            </div>

            <div class="wizard-step" v-if="wizard.step == 'share'">
                <p class="text-muted tour-profile-complete">
                    <md-icon class="margin-10">info</md-icon>
                    Invite your friends to the SageGroupy platform, it's free for everyone. The more people that use SageGroupy the more opportunities there will be for everyone.
                </p>
                <div class="col-lg-10 col-lg-offset-1">

                    <div v-for="(friend,index) in friendInvites" :key="index">
                        <div class="col-lg-6">
                            <md-icon class="pull-left margin-right-20 margin-top-20 md-primary">account_circle</md-icon>
                            <md-input-container class="auto-width">
                                <label>Friends Name</label>
                                <md-input v-model="friend.name"></md-input>
                            </md-input-container>
                        </div>
                        <div class="col-lg-6">
                            <md-input-container>
                                <label>Friends Email</label>
                                <md-input v-model="friend.email"></md-input>
                            </md-input-container>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='references'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>

                <md-button
                        class="md-primary md-raised pull-right no-margin-right margin-top-20"
                        @click="sendInvites('finish')">
                    Finish
                    <md-icon>navigate_next</md-icon>
                </md-button>

                <div class="clearfix"></div>
                <div class="text-muted text-center margin-top-20">
                    <a href='#' class="text-muted" @click.prevent="wizard.step='finish'">Skip This Step (Not Recommended)</a>
                </div>
            </div>

            <div class="wizard-step" v-if="wizard.step == 'finish'">
                <p class="text-muted tour-profile-complete text-center">
                    <md-icon class="margin-10">info</md-icon>
                    You're ready to start using SageGroupy! Before you start, please read over our suggestions for getting the most out of the SageGroupy platform.
                </p>
                <div class="col-lg-10 col-lg-offset-1">

                    <ul class="list-group">
                        <li class="list-group-item">
                            <md-icon class="md-primary margin-right-20 pull-left margin-top-5">share</md-icon>
                            <h4 class="text-primary pull-left margin-top-5 margin-bottom-5">Share Your Profile</h4>
                            <div class="clearfix"></div>
                            <hr class="green-hr margin-bottom-5 margin-top-5"/>
                            <p>One of the best ways to get new clients is to use your existing network of friends and colleauges. You can share your profile via the Share button at the top of each page. </p>
                            <p>You may be surprised how much work even a small network of friends can bring. You may think they already know you are freelancing but by letting them know directly you will reinforce the fact that you are availabe if they, or any of their friends, ever need work
                               completed in your area of expertise.</p>

                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <md-icon class="md-primary margin-right-20 pull-left margin-top-5">find_in_page</md-icon>
                            <h4 class="text-primary pull-left margin-top-5 margin-bottom-5">Add Relevant Skills to Your Profile</h4>
                            <div class="clearfix"></div>
                            <hr class="green-hr margin-bottom-5 margin-top-5"/>
                            <p>Skills are an important way to let clients know what you work you are capable of performing. SageGroupy allows you to add up to 12 skills to your profile. Each skill can include your experience level, desired hourly rate and how many years of experience you have. </p>
                            <p>Make sure your skills are always up to date and accurate. When clients are looking for new freelancers they primarily use skills to narrow down their search.</p>

                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <md-icon class="md-primary margin-right-20 pull-left margin-top-5">assignment_turned_in</md-icon>
                            <h4 class="text-primary pull-left margin-top-5 margin-bottom-5">Add Previous Job References</h4>
                            <div class="clearfix"></div>
                            <hr class="green-hr margin-bottom-5 margin-top-5"/>
                            <p>Clients primarily choose freelancers based on your portfolio of previous work and reviews from previous clients. To make this easier, you can add past jobs you have completed to your SageGroupy profile. Past jobs can include a description of the job as well as any
                               related media and will be displayed on your public profile. </p>
                            <p>You also have the option to invite your previous clients to provide a review. An email will be sent to the client asking them to rate your work and provide a brief message regarding the work you performed. </p>

                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <md-icon class="md-primary margin-right-20 pull-left margin-top-5">card_membership</md-icon>
                            <h4 class="text-primary pull-left margin-top-5 margin-bottom-5">Create or Join an Agency</h4>
                            <div class="clearfix"></div>
                            <hr class="green-hr margin-bottom-5 margin-top-5" />

                            <p>Agencies are a great way to get more work and expand your reach within the SageGroupy platform. Anyone can create an agency and invite other users to join their agency. For example, if you are a graphic designer you may want to join an agency that has members who compliment your skills, such as web designers or game programmers.</p> <p>To get started you can either create your own agency and invite other freelancers, or ask to join an existing agency.</p>
                        </li>
                    </ul>


                </div>
                <div class="clearfix"></div>
                <md-button class="md-primary md-raised pull-left no-margin-left margin-top-20" @click="wizard.step='share'">
                    <md-icon>navigate_before</md-icon>
                    Back
                </md-button>

                <md-button
                        class="md-primary md-raised pull-right no-margin-right margin-top-20"
                        @click="wizard.step='complete'">
                    Go To Dashboard
                    <md-icon>navigate_next</md-icon>
                </md-button>
            </div>

            <div class="clearfix"></div>

        </div>

        <img :src="cropper.imgDataUrl">

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

        <!-- Freelancer ASk For Recommendation Search Modal -->
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
                <md-input type="text" name='url' v-model="shared.link.url" required placeholder="Enter the URL for your link."></md-input>
                <span v-if="shared.errors.url" class="md-error">{{ shared.errors.url[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : shared.errors.title  }">
                <label>Link Title</label>
                <md-input type="text" name='url' v-model="shared.link.title" required placeholder="Enter the title for your link."></md-input>
                <span v-if="shared.errors.title" class="md-error">{{ shared.errors.title[0] }}</span>
            </md-input-container>

            <md-input-container v-bind:class="{ 'md-input-invalid' : shared.errors.description  }">
                <label>Link Description (Optional)</label>
                <md-input type="text" name='url' v-model="shared.link.description" placeholder="Enter a short description of this link."></md-input>
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


            /* Load countries and cities */
			this.$http.get('/js/countries.json').then((response) => {
				this.countries = response.body;
			}, (response) => {
				console.log("Error loading countries");
			});

            /* Set Active Step Based on Hash */
			this.urlHash = window.location.hash.substr(1);
			if (this.urlHash.toString().length > 1) {
				this.wizard.step = this.urlHash;
            }

            this.refreshProfile();

            setTimeout(function() {
                $(".sageImage").fancybox();
            },3000);

        },
        components: {
            VueEditor
        },
        props: ['profileId'],
        watch: {
        	'wizard.step': function(newStep) {
        		console.log(this.wizard.step);
        		console.log(newStep);

				$("html, body").animate({scrollTop: 0}, "slow");

        		if (newStep == 'complete') {
						document.location = "/dashboard";
                }
            }
        },
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
                wizard: {
                	step: 'profile'
                },
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
                cities: [],
                friendInvites: [
                    {name: '', email: ''},
                    {name: '', email: ''},
                    {name: '', email: ''},
                    {name: '', email: ''},
                    {name: '', email: ''},
                ]
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
			autoCompleteMatch: function (suggestion, query) {
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
            addRecommendation() {
            	console.log('adding recommendation');
                appBus.$emit('addedRecommendation', this.selectedFreelancer, this.recommendationSnippet);
            },
			askForRecommendation() {
				var message = {
					from_user_id: this.shared.user.id,
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
           sendInvites(nextStep) {
			   this.$root.showNotification('Sending invites, please wait.');
                this.errors = {}
                this.state.saving = true;
                this.$http.post('/apiv1/notifications/inviteFriends', {friendInvites: this.friendInvites}).then((response) => {

                    this.state.saving = false;
                    this.$root.showNotification(response.body.message);

                    if (nextStep) {
                    	this.wizard.step = nextStep;
                    }
                }, (response) => {
                    this.state.saving = false;
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    }
                });
            },
            saveProfile(nextStep) {

                this.errors = {}
                this.state.saving = true;
                this.$http.post('/apiv1/profile/update/' + this.shared.profile.id, this.shared.profile).then((response) => {

                    this.state.saving = false;
                    this.$root.showNotification(response.body.message);
                    this.refreshProfile(true);

                    if (nextStep) {
                    	this.wizard.step = nextStep;
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
            refreshProfile(preventNotification) {
                this.$http.get('/apiv1/profile/' + this.profileId).then((response) => {
                    this.shared.profile = response.body.user;
                    if (!_.isUndefined(this.shared.profile.bio)) {
                        this.userBio = this.shared.profile.bio;
                    }
                }, (response) => {
                	if (!preventNotification) {
						this.$root.showNotification(response.body.message);
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
                this.$forceUpdate();
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
