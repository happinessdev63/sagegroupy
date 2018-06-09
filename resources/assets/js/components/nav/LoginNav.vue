<!--

Usage: <sage-nav> </sage-nav>
Properties: current-page [Declares which page should be active in navigation.]

-->

<template>
    <md-toolbar class="md-transparent">
        <md-button
                :class="currentPage == 'home' ? 'md-primary' : '' "
                href="/">
           Home
        </md-button>
        <md-button
                :class="currentPage == 'login' ? 'md-primary' : '' "
                href="/login">
            Login
        </md-button>
        <md-button
                :class="currentPage == 'signup' ? 'md-primary' : '' "
                href="/register">
            Sign Up
        </md-button>
        <md-button
                :class="currentPage == 'support' ? 'md-primary' : '' "
                href="/support">
            Contact Us
        </md-button>

        <div style="flex: 1"></div>


        <div class="pull-right">

        </div>
    </md-toolbar>
</template>


<script>
    export default {
        mounted() {
            console.log(' Nav component ready.')
        },
        props: ['currentPage'],
        data() {
            return {}
        },
        methods: {
            toggleSidebar(ref) {
                this.$root.toggleSidebar(ref);
            },
        }
    }
</script>

