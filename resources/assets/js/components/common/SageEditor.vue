<template>
    <div id="quillWrapper">
        <div :ref="refId" :id="refId" style="min-height: 300px;"></div>

        <button v-if="useSaveButton" class="save-button"
                @click="saveContent">
            {{ buttonText ? buttonText : 'Save Content' }}
        </button>

        <div v-if="showPreview" ref="livePreview" class="ql-editor"></div>

    </div>
</template>

<script type="text/babel">
	import Quill from 'quill';

	var defaultToolbar = [
		['bold', 'italic', 'underline', 'strike'],
		['blockquote', 'code-block'],

		[{'list': 'ordered'}, {'list': 'bullet'}],

		[{'indent': '-1'}, {'indent': '+1'}],
		[{'header': [1, 2, 3, 4, 5, 6, false]}],

		[{'color': []}, {'background': []}],
		[{'font': []}],
		[{'align': []}],

		['clean']
	];

	export default {
		mounted() {

			let vm = this;
			vm.quill = new Quill(vm.$refs[vm.refId], {
				modules: {
					toolbar: this.toolbar
				},
				placeholder: this.placeholder ? this.placeholder : '',
				theme: 'snow'
			});

			console.log(vm.refId + ' quill created');

			vm.editor = document.querySelector('#' + vm.refId + ' .ql-editor');

			if (vm.editorContent) {
				vm.editor.innerHTML = vm.editorContent
			}

			if (vm.$refs.livePreview !== undefined || false) {

				vm.quill.on('text-change', function () {
					vm.$refs.livePreview.innerHTML = vm.editor.innerHTML;
					vm.$emit('editor-updated', vm.editor.innerHTML);
				});

			} else {

				vm.quill.on('text-change', function () {
					vm.$emit('editor-updated', vm.editor.innerHTML);
				});

			}
		},
		props: {
			editorContent: String,
			placeholder: String,
			buttonText: String,
			editorToolbar: Array,
			refId: String,
			useSaveButton: {
				type: Boolean,
				default: function() {
					return true
				}
			},
			showPreview: {
				type: Boolean,
				default: function() {
					return false
				}
			}
		},
		data: function () {
			return {
				quill: null,
				editor: null,
				toolbar: this.editorToolbar ? this.editorToolbar : defaultToolbar
			}
		},
		watch: {
			editorContent: function () {
				this.editor.innerHTML = this.editorContent
			}
		},
		methods: {
			saveContent: function (value) {
				this.$emit('save-content', this.editor.innerHTML)
			}
		}
	}


</script>

<style scoped>

    #quill-container {
        height: 400px;
    }

</style>
