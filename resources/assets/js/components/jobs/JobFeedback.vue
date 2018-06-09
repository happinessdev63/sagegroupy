<template>
    <!-- End Job Modal -->
    <div>

        <!-- Freelancer Feedback -->
            <div v-if="shared.job.freelancer">
                <ui-textbox
                        class="margin-top-40"
                        required
                        enforce-maxlength
                        :invalid="isFeedbackValid"
                        error="Please enter feedback for this job."
                        :label="'Feedback for ' + shared.job.freelancer.name"
                        placeholder="Enter your feedback..."
                        :multi-line="true"
                        :maxlength="2048"
                        @input="updateFeedback"
                        v-model="feedback"
                ></ui-textbox>

                <form>
                    <label v-bind:class="{ 'text-accent' : !errors.freelancer_rating, 'text-invalid' : errors.freelancer_rating }">
                        How would you rate {{ shared.job.freelancer.first_name }}?
                    </label>
                    <sage-rating :items="ratings"
                                 :value="0"
                                 kind="checkmark"
                                 @change="onRating">
                    </sage-rating>
                </form>
            </div>

        <!-- Agency feedback -->
        <div v-if="shared.job.agency">
            <ui-textbox
                    class="margin-top-40"
                    required
                    enforce-maxlength
                    :invalid="isFeedbackValid"
                    error="Please enter feedback for this job."
                    :label="'Feedback for ' + shared.job.agency.name"
                    placeholder="Enter your feedback..."
                    :multi-line="true"
                    :maxlength="2048"
                    @input="updateFeedback"
                    v-model="feedback"
            ></ui-textbox>

            <form>
                <label v-bind:class="{ 'text-accent' : !errors.freelancer_rating, 'text-invalid' : errors.freelancer_rating }">
                    How would you rate {{ shared.job.agency.name }}?
                </label>
                <sage-rating :items="ratings"
                             :value="0"
                             kind="checkmark"
                             @change="onRating">
                </sage-rating>
            </form>
        </div>


        <div class="margin-top-20 text-right">
                <md-button  class="md-primary pull-right" @click="endJob">Save Feedback</md-button>
            </div>
    </div>

</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log('End job component ready.')
            if (this.shared.job.freelancer_feedback) {
                this.feedback = this.shared.job.freelancer_feedback;
            }
        },
        data() {
            return {
                errors: {},
                user: window.Laravel.user,
                shared: window.sageSource,
                feedback: '',
                ratings: [
                    {
                        title: '5 Stars',
                        value: 5
                    },
                    {
                        title: '4 Stars',
                        value: 4
                    },
                    {
                        title: '3 Stars',
                        value: 3
                    },
                    {
                        title: '2 Stars',
                        value: 2
                    },
                    {
                        title: '1 Star',
                        value: 1
                    }
                ]
            }
        },
        computed: {
            isFeedbackValid: function () {
                if (!this.errors.freelancer_feedback) {
                    return false;
                }
                if (this.errors.freelancer_feedback.length > 0) {
                    return true;
                }
                return false;
            }
        },
        methods: {
            onRating(rating) {
                this.shared.job.freelancer_rating = rating;
            },
            updateFeedback(value) {
              this.shared.job.freelancer_feedback = value;
            },
            endJob() {

                var apiUrl = '/apiv1/jobs/clientFeedback/' + this.shared.job.id;
                this.$http.post(apiUrl, this.shared.job).then((response) => {
                    this.$root.showNotification(response.body.message);
                    //appBus.$emit('endJob', this.shared.job);
                }, (response) => {
                    if (response.body.status == 'error' && response.body.message) {
                        this.$root.showNotification(response.body.message);
                    } else {
                        this.errors = response.body;
                    }
                });

            },
            open() {
                this.$refs['endJobModal'].open();
            },
            close() {
                this.$refs['endJobModal'].close();
            },
        }
    }
</script>