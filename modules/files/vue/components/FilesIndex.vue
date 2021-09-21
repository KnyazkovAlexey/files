<template>
    <div class="file-list">
        <h1>{{ pageTitle }}</h1>

        <router-link class="btn btn-success btn-sm mb-2" to="/files/add">
            <span class="font-weight-bold">+</span> Добавить
        </router-link>

        <div class="spinner-wrapper">
            <v-server-table
                :columns="table.columns"
                :options="table.options"
                @loading="onLoading"
                @loaded="onLoaded">
                <div class="spinner" v-if="isLoading" slot="afterTable">
                    <div class="overlay-loader"></div>
                    <clip-loader size="50px" class="clip-loader"></clip-loader>
                </div>
            </v-server-table>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import axios from 'axios';
    import {ServerTable} from 'vue-tables-2';
    import SortControl from 'CommonComponents/SortControl.vue';
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue';

    Vue.use(ServerTable, {}, false, 'bootstrap4', {
        sortControl: SortControl,
    });

    export default {
        name: 'FilesIndex',
        components: {
            ClipLoader,
        },
        data() {
            return {
                pageTitle: 'Файлы',
                isLoading: false,
                table: {
                    columns: [
                        'original_name',
                        'uploaded_at',
                    ],
                    options: {
                        filterable: false,
                        headings: {
                            original_name: 'Оригинальное наименование',
                            uploaded_at: 'Дата загрузки',
                        },
                        texts: {
                            limit: '',
                            count: '',
                            noResults: 'Ничего не найдено.',
                            loading: 'Загрузка...',
                        },
                        requestFunction: function (data) {
                            let params = {
                                page: data.page,
                                'per-page': data.limit,
                            };

                            if (data.orderBy) {
                                params['sort'] = (data.ascending ? '' : '-') + data.orderBy;
                            }

                            return axios.get('/api/v1/files', {
                                params,
                            }).catch(function () {
                                alert('Произошла ошибка.');
                            });
                        }.bind(this),
                        responseAdapter({data}) {
                            return {
                                data: data.items,
                                count: data._meta.totalCount,
                            }
                        },
                    },
                },
            };
        },
        created() {
            document.title = this.pageTitle;
        },
        methods: {
            onLoading() {
                this.isLoading = true;
            },
            onLoaded() {
                this.isLoading = false;
            },
        },
    }
</script>