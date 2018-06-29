<!--

Usage: <sage-login> </sage-login>
Properties: redirect-url [Where to redirect after a successful login]

-->

<template>
    <form novalidate @submit.stop.prevent="submit">

        <md-input-container v-bind:class="{ 'md-input-invalid' : errors.name  }">
            <label>Name</label>
            <md-input type="text" required  v-model="form.name"></md-input>
            <span v-if="errors.name" class="md-error">{{ errors.name[0] }}</span>
        </md-input-container>

        <md-input-container v-bind:class="{ 'md-input-invalid' : errors.email  }">
            <label>Email</label>
            <md-input type="text" required  v-model="form.email"></md-input>
            <span v-if="errors.email" class="md-error">{{ errors.email[0] }}</span>
        </md-input-container>

        <md-input-container v-bind:class="{ 'md-input-invalid' : errors.password  }">
            <label>Password</label>
            <md-input type="password" required v-model="form.password"></md-input>
            <span v-if="errors.password" class="md-error">{{ errors.password[0] }}</span>
        </md-input-container>

        <md-input-container v-bind:class="{ 'md-input-invalid' : errors.password_confirmation  }">
            <label>Confirm Password</label>
            <md-input type="password" required v-model="form.password_confirmation"></md-input>
        </md-input-container>

        <div class="text-black font-weight-400">
            <md-radio class="md-primary" v-model="form.type" id="type1" name="type1" md-value="freelancer">Freelancer</md-radio>
            <md-radio class="md-primary" v-model="form.type" id="type2" name="type1" md-value="client">Client</md-radio>
            <md-radio class="md-primary" v-model="form.type" id="type3" name="type1" md-value="client_freelancer">Both</md-radio>
        </div>

        <md-button v-if="state.loggingIn" class="md-raised md-primary">
            Sign Up
            <md-spinner :md-size="10" md-indeterminate class="md-accent margin-top-10 margin-left-5"></md-spinner>
        </md-button>

        <md-button v-if="!state.loggingIn" @click="register()" class="md-raised md-primary">Sign Up</md-button>

        <p class="margin-top-10">Aready have an account? <a href="/login" class="link-primary">Login</a></p>
    </form>
</template>


<script>
    export default {
        mounted() {
            console.log('Register component ready.')
        },
        props: ['redirectUrl'],
        data() {
            return {
                errors: {
                    name: null,
                    email: null,
                    password: null,
                    login: null
                },
                form: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    type: 'freelancer',
                    redirect: this.redirectUrl,
                },
                state: {
                    loggingIn: false
                }
            }
        },
        methods: {
            register: function () {
                this.errors = {}
                this.state.loggingIn = true;

                this.$http.post('/apiv1/register', this.form).then((response) => {
                    console.log(response);
                    this.state.loggingIn = false;
                    // window.location = response.body.redirect;
                }, (response) => {
                    console.log(response);
                    this.state.loggingIn = false;
                    this.errors = response.body;
                });
            }
        }
    }
</script>
