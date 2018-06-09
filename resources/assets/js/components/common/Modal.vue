<template>
    <md-dialog :ref="name" v-cloak class="max-width-600 margin-auto">
        <md-dialog-content class="no-padding-top">
            <md-button class="pull-right no-padding no-margin auto-width auto-min-width" @click="$refs[name].close();"><md-icon>close</md-icon></md-button>
            <div class="clearfix"></div>
            <div class="text-center">
                <img v-if="showLogo && logoSize == 'small' " src="/img/logo_xs.png" class="center-block"/>
                <img v-if="showLogo && logoSize == 'xs' " src="/img/logo_xs.png" class="center-block" width="200" height="95"/>
            </div>
            <h4>
                {{ title }}
            </h4>
            <hr class="green-hr"/>
            <slot>
            </slot>
        </md-dialog-content>
    </md-dialog>
</template>

<script>
    export default {
        mounted() {
            console.log('Modal omponent ready.')

            var vm = this;
            setTimeout(function () {
                vm.$refs[vm.name].open();
            }, this.delay);

        },
        props: {
        	'delay' : Number,
			'title' : String,
            'showLogo' : Boolean,
            'name' : String,
            'logoSize' : {
        		type: String,
                default: 'small'
            }
		},
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