<template>
    <div>

        <div class="col-lg-6 col-sm-12">
            <h4>Links & Additional Profiles</h4>
            <hr class="green-hr margin-top-5"/>
            <md-list v-if="links.length > 0" class="custom-list md-dense">
                <md-list-item v-for="(link,index) in links">

                    <md-icon class="font-size-40">
                        bookmark
                    </md-icon>

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <div class="margin-bottom-10">
                            <span> <a :href="link.url" rel="nofollow" target="_blank" class="link-primary">{{ link.title }}</a></span>
                        </div>
                        <p>{{ link.short_description }}</p>

                    </div>


                    <div v-if="link.files.length > 0" class="margin-top-10 pull-right">
                        <a v-for="(file,index) in link.files" v-if='file.is_image' class="sageImage" :href="file.url" rel="image-files">
                            <img :src="file.url" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">
                        </a>
                    </div>

                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <div class="col-lg-6 col-sm-12">
            <h4>Recommendations</h4>
            <hr class="green-hr margin-top-5"/>
            <!-- Recommendations -->
            <md-list class="custom-list md-dense" v-if="shared.profile.recommended_users.length > 0 || shared.profile.recommendations.length > 0">
                <md-list-item v-for="user in shared.profile.recommended_users">

                    <img :src="user.freelancer.avatar" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <div class="margin-bottom-10">
                            <span> <a :href="user.freelancer.profile_link" class="link-primary">Recommends {{ user.freelancer.name}}</a></span>
                        </div>
                        <p v-if="user.snippet.length == 0">
                            {{ user.owner.name }} recommends {{ user.freelancer.name }} as an additional freelancer with complimentary skills.
                        </p>
                        <p v-else>{{ user.snippet }}</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
                <md-list-item v-for="user in shared.profile.recommendations">

                    <img :src="user.freelancer.avatar" class="img-thumbnail margin-right-10" style="width: 45px; height: 45px;">

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <div class="margin-bottom-10">
                            <span> <a :href="user.owner.profile_link" class="link-primary">Recommended By {{ user.owner.name}}</a></span>
                        </div>
                        <p v-if="user.snippet.length == 0">
                            {{ user.owner.name }} recommended {{ user.freelancer.name }} as an additional freelancer with complimentary skills.
                        </p>
                        <p v-else>{{ user.snippet }}</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <!-- No data -->
        <div v-if="(agency && links.length == 0)
        || (!agency && links.length == 0 && shared.profile.recommended_users.length == 0 && shared.profile.recommendations == 0)">
            <md-list class="custom-list md-dense">
                <md-list-item>
                    <md-icon class="font-size-30">
                        announcement
                    </md-icon>

                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <p v-if="!agency">{{ shared.profile.name}} has not added any links or recommendations yet.</p>
                        <p v-else>This agency has not added any links yet.</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Profile links component ready.')
        },
        data() {
            return {
                shared: window.sageSource,
                errors: {},
            }
        },
        props: {
            agency: {
                type: Number,
                default: null
            }
        },
        computed: {
            links: function () {
                if (this.agency) {
                    return this.shared.agency.links;
                }
                return this.shared.profile.links;
            }
        },
        methods: {}
    }
</script>