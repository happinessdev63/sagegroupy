<template>
    <div>
        <img v-if="rating >= 1" src="/img/star.png" width="24" height="24"/>
        <img v-if="rating >= 2" src="/img/star.png" width="24" height="24"/>
        <img v-if="rating >= 3" src="/img/star.png" width="24" height="24"/>
        <img v-if="rating >= 4" src="/img/star.png" width="24" height="24"/>
        <img v-if="rating >= 5" src="/img/star.png" width="24" height="24"/>

        <img v-if="rating < 5" src="/img/star_empty.png" width="24" height="24"/>
        <img v-if="rating < 4" src="/img/star_empty.png" width="24" height="24"/>
        <img v-if="rating < 3" src="/img/star_empty.png" width="24" height="24"/>
        <img v-if="rating < 2" src="/img/star_empty.png" width="24" height="24"/>
        <img v-if="rating < 1" src="/img/star_empty.png" width="24" height="24"/>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
        props: ['rating'],
        data: () => ({
            state: {
                loading: false,
            },
            shared: window.sageSource
        }),
        computed: {

        },
        methods: {

        }
    }
</script>