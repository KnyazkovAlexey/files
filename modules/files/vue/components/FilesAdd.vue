<template>
    <div class="file-add">
        <h1>{{ pageTitle }}</h1>

        <router-link class="btn btn-info btn-sm mb-4" to="/files">Перейти к списку</router-link>

        <b-alert v-model="alert.isVisible" :variant="alert.variant" dismissible>
            {{ alert.message }}
        </b-alert>

        <div class="spinner-wrapper">
            <b-form-file
                class="mb-2"
                v-model="files"
                ref="file-input"
                :state="files.length > 0"
                browse-text="Выбрать"
                placeholder="Выберите файлы или перетащите сюда..."
                drop-placeholder="Перетащите файлы сюда..."
                multiple>
            </b-form-file>

            <b-button class="mr-1" variant="success" v-on:click="submitFiles()">Загрузить</b-button>
            <b-button @click="clearForm()">Сбросить</b-button>

            <div class="spinner" v-if="isLoading">
                <div class="overlay-loader"></div>
                <clip-loader size="50px" class="clip-loader"></clip-loader>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue';

    export default {
        name: 'FilesAdd',
        components: {
            ClipLoader,
        },
        data() {
            return {
                pageTitle: 'Добавление файлов',
                isLoading: false,
                alert: {
                    'variant': '',
                    'message': '',
                    'isVisible': false,
                },
                files: [],
            }
        },
        created() {
            document.title = this.pageTitle;
        },
        methods: {
            submitFiles() {
                let vm = this;

                vm.hideAlert();
                vm.onLoading();

                let formData = new FormData();
                for (let i = 0; i < vm.files.length; i++) {
                    formData.append('files[' + i + ']', vm.files[i]);
                }

                axios.post( '/api/v1/files/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }).then(function() {
                    vm.clearForm();
                    vm.showSuccessAlert('Успешно загружено.');
                }).catch(error => {
                    vm.showErrorAlert(error.response.data.message);
                }).then(function () {
                    vm.onLoaded();
                });
            },
            showSuccessAlert(message) {
                this.showAlert(message, 'success');
            },
            showErrorAlert(message) {
                this.showAlert(message, 'danger');
            },
            showAlert(message, variant = 'info') {
                this.alert.message = message;
                this.alert.variant = variant;
                this.alert.isVisible = true;
            },
            hideAlert() {
                this.alert.isVisible = false;
            },
            clearForm() {
                this.files = [];
            },
            onLoading() {
                this.isLoading = true;
            },
            onLoaded() {
                this.isLoading = false;
            },
        },
    }
</script>