require('./app');

window.Vue = require('vue');

import TopCard from './components/TopCard.vue';
Vue.component('top-card', TopCard);

var app = new Vue({
    el: '#app',
    methods: {
        updateSummoner: function() {
            let form = document.getElementById('updateSummoner');
            form.submit();
        },
    }
 });

