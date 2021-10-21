/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import vuetify from './plugins/vuetify';
import router from './plugins/router';
import store from './plugins/store';
import apexchart from './plugins/apexchart';
import Axios from './plugins/axios';

import App from './layouts/App.vue';
Vue.prototype.$admin = Axios

const app = new Vue({
    router,
    store,
    vuetify,
    el: '#app',
    render: h => h(App)
});
