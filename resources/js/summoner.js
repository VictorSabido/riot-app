require('./app');

window.Vue = require('vue');
// Vue.component('top-card', require('./components/TopCard.vue'));
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// Vue.component("./components/ExampleComponent.vue", MyComponent)

import TopCard from './components/TopCard.vue';

Vue.component('top-card', TopCard);

var app = new Vue({
    el: '#app'
 });

