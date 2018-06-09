<template>
    <div>
        <md-button class="md-primary pull-right">
            <md-icon>add</md-icon>
            Add Item
        </md-button>
        <div class="clearfix"></div>
        <md-list class="custom-list md-dense">
            <md-list-item>
                <md-icon class="font-size-30">
                    bookmark
                </md-icon>

                <div class="md-list-text-container margin-top-20 padding-bottom-10">
                    <div class="margin-bottom-10">
                        <span> <a href="#" class="link-primary">LinkedIn Profile</a></span>
                    </div>
                    <p>Link to my LinkedIn profile for more information about my work history.</p>
                </div>
                <md-divider class="md-inset"></md-divider>
            </md-list-item>
            <md-list-item>
                <md-icon class="font-size-30">
                    bookmark
                </md-icon>

                <div class="md-list-text-container margin-top-20 padding-bottom-10">
                    <div class="margin-bottom-10">
                        <span><a href="#" class="link-primary">Deviant Art Profile</a></span>
                    </div>
                    <p>Link to my Deviant Art for more samples of my creative works.</p>
                </div>
                <md-divider class="md-inset"></md-divider>
            </md-list-item>
            <md-list-item>
                <md-icon class="font-size-30">
                    bookmark
                </md-icon>

                <div class="md-list-text-container margin-top-20 padding-bottom-10">
                    <div class="margin-bottom-10">
                        <span> <a href="#" class="link-primary">Facebook Profile</a></span>
                    </div>
                    <p>Link to my Facebook profile for more information about my life & hobbies.</p>
                </div>
                <md-divider class="md-inset"></md-divider>
            </md-list-item>

        </md-list>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Agency edit links component ready.')
        },
        data() {
            return {
                user: window.Laravel.user,
                shared: window.sageSource,
                errors: {},
            }
        },
        methods: {}
    }
</script>