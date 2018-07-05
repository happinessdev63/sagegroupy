<!--

Usage: <sage-login> </sage-login>
Properties: redirect-url [Where to redirect after a successful login]

-->

<template>
    <div>
        <sage-image-cropper
                field="img"
                @crop-upload-success="cropUploadSuccess"
                @crop-upload-fail="cropUploadFail"
                v-model="cropper.show"
                :width="322"
                :height="160"
                url="/apiv1/files/avatar/companylogo"
                lang-type='en'
                :params="cropper.params"
                img-format="png">
        </sage-image-cropper>
        <img :src="cropper.imgDataUrl" style="width:100%">
    </div>
</template>
<script type="text/babel">
    import {VueEditor} from 'vue2-editor';

    export default {
        mounted() {
            console.log(' Download profile component ready.')
            var vm = this;

        		appBus.$on('editAvatar', function (ref) {
              console.log(ref);
        			vm.toggleCropper();
        		});

            setTimeout(function() {
                $(".sageImage").fancybox();
            },3000);

        },
        components: {
            VueEditor
        },
        props: ['imageurl'],
        data() {
            return {
                urlHash: '',
                activeTab: 0,
                shared: window.sageSource,
                avgRate: null,
                shareEmail: '',
                selectedFile : null,
                userBio: '',
                errors: {},
                suggestions: [],
                state: {
                    saving: false,
                    freelancerSearchLoading: false
                },
                notifyPosition: 'right',
                allowHtmlNotifications: true,
                queueNotifications: true,
                cropper: {
                    show: false,
                    params: {
                        token: '123456798',
                        name: 'avatar'
                    },
                    imgDataUrl: this.imageurl,
                },
            }
        },
        filters: {
            limitTo: function (string, value) {
                if (!string) {
                    return '';
                }

                if (string.length <= value) {
                    return string;
                }

                return string.substring(0, value) + '...';
            }
        },
        methods: {
            saveLink() {
                appBus.$emit('savedLink', this.shared.link);
            },

            showNotification(message) {
                this.$refs.notifyContainer.createSnackbar({
                    message: message,
                    action: '',
                    actionColor: 'accent',
                    duration: 5000
                });
            },
            /* Cropper */
            toggleCropper() {
                this.cropper.show = !this.cropper.show;
            },
            cropUploadSuccess(jsonData, field){
                this.cropper.imgDataUrl = jsonData.url;
                this.cropper.show = false;
                // this.$root.showNotification("Your avatar has been updated.")
                // this.$root.makePdf();

            },
            cropUploadFail(status, field){
                this.$root.showNotification("There was an error uploading your avatar, please try again.")
            },
        }
    }
</script>
