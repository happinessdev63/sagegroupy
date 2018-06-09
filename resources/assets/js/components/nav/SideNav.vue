<!--

Usage: <sage-sidenav> </sage-sidenav>
Requires: Window.Laravel.User

-->

<template>
    <md-sidenav class="md-left md-fixed" ref="sideNav">
        <md-toolbar class="md-account-header">
            <md-list class="md-transparent">
                <md-list-item class="md-avatar-list">
                    <md-avatar class="md-large">
                        <img src="https://placeimg.com/64/64/people/8" alt="People">
                    </md-avatar>
                    <div class="pull-left margin-left-10">
                        <h4>{{ user.name }}</h4>
                        <span class="font-size-10">{{ user.email }}</span>
                    </div>
                </md-list-item>
            </md-list>
        </md-toolbar>

        <md-list>
            <md-list-item href="/logout" class="md-primary md-raised">
                <md-icon>exit_to_app</md-icon>
                <span>Logout</span>
            </md-list-item>

            <md-list-item href="/dashboard" class="md-primary md-raised">
                <md-icon>dashboard</md-icon>
                <span>Dashboard</span>
            </md-list-item>

            <md-list-item href="/profile" class="md-primary md-raised">
                <md-icon>account_box</md-icon>
                <span>View Profile</span>
            </md-list-item>

            <md-list-item :href="profileEditLink(user)" class="md-primary md-raised">
                <md-icon>edit</md-icon>
                <span>Edit Profile</span>
            </md-list-item>

            <md-list-item v-if="user.is_client" href="/jobs/create" class="md-primary md-raised">
                <md-icon>assignment</md-icon>
                <span>Create a Job</span>
            </md-list-item>

            <md-list-item v-if="user.is_freelancer" href="/jobs/createReference" class="md-primary md-raised">
                <md-icon>assignment_ind</md-icon>
                <span>Add Reference Job</span>
            </md-list-item>

            <md-list-item v-if="user.is_freelancer" href="/agencies" class="md-primary md-raised">
                <md-icon>card_membership</md-icon>
                <span>View Agencies</span>
            </md-list-item>

            <md-list-item v-if="user.is_freelancer" href="/agencies/create" class="md-primary md-raised">
                <md-icon>card_membership</md-icon>
                <span>Create an Agency</span>
            </md-list-item>
        </md-list>
    </md-sidenav>
</template>


<script>
    export default {
        mounted() {
            console.log(' Side Nav component ready.')
        },
        props: ['loggedIn'],
        data() {
            return {
                user: window.Laravel.user
            }
        },
        methods : {
            toggleSideNav: function() {
                this.$refs['sideNav'].toggle();
            },
            profileEditLink(user)
            {
                return "/profile/edit/" + user.id;
            }
        }
    }
</script>
