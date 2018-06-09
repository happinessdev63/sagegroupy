<template>
    <div>
        <md-button v-if="!state.showUploader" class="md-primary pull-right" @click.native="state.showUploader = true">
            <md-icon>add</md-icon>
            Add New File or Media
        </md-button>

        <md-button v-if="state.showUploader" class="md-primary pull-right" @click.native="state.showUploader = false">
            <md-icon>close</md-icon>
        </md-button>

        <div v-if="state.showUploader">
        <sage-dropzone
                ref="jobFiles"
                id="agencyFiles"
                v-bind:thumbnailHeight="120"
                v-bind:thumbnailWidth="120"
                v-bind:showRemoveFile="true"
                :maxFileSizeInMB="20"
                url="/apiv1/files/create"
                acceptedFileTypes="image/*,application/pdf,.psd,.doc*,.odf"
                v-on:vdropzone-success="fileSuccess"
        ></sage-dropzone>
        </div>

        <div class="clearfix"></div>

        <md-list v-if="files.length > 0" class="custom-list md-double-line">
            <md-list-item v-for="(file, fileIndex) in files" :key="fileIndex">

                <div v-if="file.is_image" class="margin-right-20">
                    <a class="sageImage" :href="file.url" rel="image-files" target="_blank">
                        <img :src="file.url" class="img-thumbnail" width="80" height="80" :alt="file.name">
                    </a>
                </div>
                <div v-else class="md-icon-auto-height" style="min-width: 100px;">
                    <a :href="file.url" target="_blank">
                        <md-icon class="font-size-40">
                            assignment
                        </md-icon>
                    </a>
                </div>

                <div class="md-list-text-container" @click="editFile(file)">
                    <span>{{ file.name }}</span>
                    <p>{{ file.description }}</p>
                </div>

                <md-button class="md-icon-button md-list-action" @click="confirmDeleteFile(file)">
                    <md-icon class="md-primary">delete</md-icon>
                </md-button>
                <md-button class="md-icon-button md-list-action" @click="editFile(file)">
                    <md-icon class="md-primary">edit</md-icon>
                </md-button>

                <md-divider class="md-inset-110"></md-divider>
            </md-list-item>
        </md-list>

        <!-- No data -->
        <div v-if="files.length == 0">
            <md-list class="custom-list md-dense">
                <md-list-item>
                    <md-icon class="font-size-30">
                        announcement
                    </md-icon>
                    <div class="md-list-text-container margin-top-20 padding-bottom-10">
                        <p>You have not added files or media yet.</p>
                    </div>
                    <md-divider class="md-inset"></md-divider>
                </md-list-item>
            </md-list>
        </div>

        <div class="clearfix"></div>


        <md-dialog-confirm
                md-title="Delete this file"
                md-content-html="Are you sure you want to remove this file?"
                md-ok-text="Delete File"
                md-cancel-text="Cancel"
                @close="deleteFile"
                ref="delete-file">
        </md-dialog-confirm>        

    </div>
</template>
<script type="text/babel">
    export default {
        mounted() {
            console.log('Edit media component ready.')

			appBus.$off('savedEditedFile');
			appBus.$on('savedEditedFile', function (event) {
				appBus.$emit('savedFile');
			});
        },
        props: {
            userType: {
                type: String,
                default: null
            }
        },
        data() {
            return {
                shared: window.sageSource,
                errors: {},
                state: {
                    saving: false,
                    showUploader: false
                },
            }
        },
        computed: {
            files: function() {
                if (this.userType == 'user') {
                	return this.shared.profile.files;
                }

                if (this.userType == 'agency') {
                	return this.shared.agency.files;
                }

                return [];
            }
        },
        methods: {
			fileSuccess: function (file) {
				console.log(file);
				var response = JSON.parse(file.xhr.response);

                /* Check if the file is an image */
				var isImage = false;
				if (file.type.indexOf('image') !== -1) {
					var isImage = true;
				}

				this.files.push({
					name: file.name,
					size: file.size,
					is_image: isImage,
					id: response.id,
					path: response.path,
					url: response.url,
					'user_path': response['user_path'],
					description: 'No description set.',
					public: true,
					featured: false
				})

				appBus.$emit('savedFile');
			},
			editFile(file) {
				this.shared.file = file;
				this.openParentModal('fileModal');

			},
			saveEditedFile() {
				this.closeModal('fileModal');
				appBus.$emit('savedFile');
			},
			confirmDeleteFile(file) {
				this.shared.file = file;
				this.$refs['delete-file'].open();
			},
			deleteFile(confirmation) {
				if (confirmation !== 'ok') {
					return;
				}

				var index = this.files.indexOf(this.shared.file);
				this.files.splice(index, 1);
				this.$root.showNotification('File ' + this.shared.file.name + ' removed successfully.');
				this.shared.file = {};
				appBus.$emit('savedFile');
			},
			openModal(ref) {
				this.$refs[ref].open();
			},
			closeModal(ref) {
				this.$refs[ref].close();
			},
            openParentModal(ref) {
                appBus.$emit('openParentModal', ref);
            },
            closeParentModal(ref) {
                appBus.$emit('closeParentModal', ref);
            },
        }
    }
</script>