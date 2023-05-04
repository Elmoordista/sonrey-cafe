/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

// import Vue from 'vue'

import vuetify from './src/plugin/vuetify'
import router from './router'

import axios from 'axios'
import VueAxios from 'vue-axios'

import App from './layout';

// import firebaseHelper from './mixins/firebaseHelper.vue';

import "../css/app.css"

import VueAWN from "vue-awesome-notifications"


let options = {
    labels: {
        success: 'Success!',
        warning: 'Warning!',
        alert: 'Failed!',
        confirm: 'Failed!',
    },
    position: 'top-right',
    durations: {
        global: 2000
    }
}

Vue.use(VueAWN, options)

// Vue.mixin(firebaseHelper);

Vue.use(VueAxios, axios)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('adminhome', require('./pages/adminhome/index.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    render: h => h(App),
});
