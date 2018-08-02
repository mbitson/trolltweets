
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.Vue = require('vue');

/**
 * Include Axois and VueAxois
 */
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://localhost:8000/api';

/**
 * Include Vuetify
 */
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import colors from 'vuetify/es5/util/colors'
Vue.use(Vuetify, {
    theme: {
        primary: colors.red.darken1,
        secondary: colors.red.lighten4,
        accent: colors.indigo.base
    }
});

/**
 * Include stores
 */
import store from './appStore.js';

/**
 * Include App
 */
import App from './components/App.vue';

/**
 * Include Routing
 */
import AppRouter from './appRouter';
Vue.router = AppRouter;

/**
 * Create the admin app
 * @type {Vue}
 */
const adminApp = new Vue({
    router: AppRouter,
    el: '#app',
    render: app => app(App),
    store
});