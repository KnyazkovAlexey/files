import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import App from './components/App.vue'
import FilesIndex from './components/FilesIndex.vue'
import FilesAdd from './components/FilesAdd.vue'
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

Vue.use(VueRouter);
Vue.use(BootstrapVue);

const routes = [
    {
        path: '/files',
        component: FilesIndex
    },
    {
        path: '/files/add',
        component: FilesAdd
    },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

new Vue({
    router,
    el: '#app',
    render: h => h(App)
});