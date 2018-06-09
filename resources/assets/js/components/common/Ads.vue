<template>
    <div class="text-center hidden" :class="classes">
        <img :src="placeholder" class="img-thumbnail" :width="width" :height="height">
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Ad component ready.')
        },
        props: ['classes', 'width', 'height'],
        data: () => ({
            state: {
                loading: false,
            },
            shared: window.sageSource
        }),
        computed: {

            placeholder: function() {
                return 'http://placehold.it/' + this.width + 'x' + this.height + "?text=Ad+Placeholder";
            }

        },
        methods: {

        }
    }
</script>