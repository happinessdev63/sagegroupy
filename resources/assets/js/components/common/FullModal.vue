<template>
    <div :ref="name" v-cloak class="full-modal dark-scroll" :class="{'open' : state.isOpen, 'closed' : !state.isOpen }">
        <div class="no-padding-top">
            <md-button class="pull-right no-padding no-margin auto-width" @click="close"><md-icon>close</md-icon></md-button>
            <h3>
                {{ title }}
            </h3>
            <hr class="green-hr margin-top-5"/>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Full Modal component ready.');

			appBus.$on('openFullModal', (ref) => {
				console.log(ref);
				if (this.name == ref) {
					this.open();
				}
			});

			appBus.$on('closeFullModal', (ref) => {
				console.log(ref);
				if (this.name == ref) {
					this.close();
				}
			});
        },
        props: ['title','name'],
        data: () => ({
            state: {
                loading: false,
                isOpen: false
            },
            shared: window.sageSource
        }),
        methods: {
            open() {
            	this.state.isOpen = true;
            },
            close() {
            	this.state.isOpen = false;
            }
        }
    }
</script>