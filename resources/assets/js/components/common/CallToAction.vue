<template>
    <md-dialog ref="ctaDialog">
        <md-dialog-content class="text-center">
            <img src="/img/logo_xs.png"/>
            <h4>
                Many Clients & Freelancers Trust SageGroupy
            </h4>
            <md-button class="md-raised md-primary" :href="registerUrl">
                Sign Up Now
            </md-button>
            <p class="text-muted margin-top-10">Already have an account? <a class="link-primary" :href="loginUrl">Login to your account</a></p>
        </md-dialog-content>
    </md-dialog>
</template>

<script>
    export default {
        mounted() {
            console.log('CTA component ready.')

            var vm = this;
            if (!this.shared.user.id) {
                setTimeout(function () {
                    vm.$refs.ctaDialog.open();
                }, this.delay);
            }
        },
        props: ['delay'],
        data: () => ({
            state: {
                loading: false,
            },
            shared: window.sageSource
        }),
        computed: {
            loginUrl: function () {
                return "/login?r=" + window.location.href;
            },
            registerUrl: function () {
                return "/register?r=" + window.location.href;
            },
        },
        methods: {


        }
    }
</script>